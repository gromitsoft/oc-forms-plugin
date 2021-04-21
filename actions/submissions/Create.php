<?php

namespace GromIT\Forms\Actions\Submissions;

use GromIT\Forms\Jobs\SendNotifiesJob;
use GromIT\Forms\Models\Field;
use GromIT\Forms\Models\Form;
use GromIT\Forms\Models\Settings;
use GromIT\Forms\Models\Submission;
use GromIT\Forms\Services\Recaptcha;
use GromIT\Forms\Traits\SelfMakeable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use InvalidArgumentException;
use October\Rain\Exception\ValidationException;
use October\Rain\Support\Arr;
use System\Models\File;

class Create
{
    use SelfMakeable;

    /**
     * Saves form submission.
     *
     * @param string     $formKey
     * @param array      $data
     * @param array|null $requestData
     * @param array|null $userData
     *
     * @return Submission
     * @throws \Throwable
     */
    public function execute(string $formKey, array $data, ?array $requestData = [], ?array $userData = []): Submission
    {
        $form = $this->findForm($formKey);

        Event::dispatch('gromit.forms::form.submitting', [$form, $data, $requestData, $userData]);

        $submission = $this->createSubmission($form, $data, $requestData, $userData);

        Event::dispatch('gromit.forms::form.submitted', [$form, $submission]);

        $this->sendNotifications($submission);

        return $submission;
    }

    /**
     * @param string $formKey
     *
     * @return \GromIT\Forms\Models\Form
     * @throws \October\Rain\Exception\ValidationException
     */
    private function findForm(string $formKey): Form
    {
        /** @var Form|null $form */
        $form = Form::whereFormKey($formKey)->first();

        if ($form === null) {
            throw new ValidationException([
                'form_id' => __('gromit.forms::lang.models.submission.validation.form_id.required')
            ]);
        }

        if (!$form->is_active) {
            throw new ValidationException([
                'form_id' => __('gromit.forms::lang.actions.submissions.create.form_is_disabled')
            ]);
        }

        return $form;
    }

    private function createSubmission(Form $form, array $data, ?array $requestData, ?array $userData): Submission
    {
        return DB::transaction(function () use ($userData, $requestData, $form, $data) {
            $submission = new Submission();

            $recaptchaField = $form->getRecaptchaField();

            if ($recaptchaField) {
                $recaptcha = new Recaptcha();

                if (!$recaptcha->check(Arr::get($data, 'g-recaptcha-response'))) {
                    throw new ValidationException([
                        $recaptchaField->key => $recaptchaField->required_message
                            ?: __('gromit.forms::lang.actions.submissions.create.recaptcha_confirm')
                    ]);
                }

                unset($data['g-recaptcha-response']);
            }

            $validation = validator(
                $data,
                $form->getValidationRules(),
                $form->getValidationMessages()
            );

            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            $nonFileFields = $form
                ->fields()
                ->where('type', '!=', Field::TYPE_UPLOAD)
                ->pluck('key')
                ->all();

            $nonFiles = Arr::only($data, $nonFileFields);

            $submission->form_id      = $form->id;
            $submission->data         = $nonFiles;
            $submission->request_data = $requestData;
            $submission->user_data    = $userData;
            $submission->save();

            $fileFields = $nonFileFields = $form
                ->fields()
                ->where('type', Field::TYPE_UPLOAD)
                ->pluck('key');

            if ($fileFields->isNotEmpty()) {
                $files = Arr::only($data, $fileFields->all());
            } else {
                $files = [];
            }

            foreach ($files as $key => $uploaded) {
                /** @var \GromIT\Forms\Models\Field|null $field */
                $field = $submission
                    ->form
                    ->fields()
                    ->where('key', $key)
                    ->where('type', Field::TYPE_UPLOAD)
                    ->first();

                if ($field === null) {
                    throw new InvalidArgumentException(
                        __('gromit.forms::lang.actions.submissions.create.recaptcha_confirm', ['key' => $key])
                    );
                }

                if ($field->uploadIsMultiple()) {
                    foreach ($uploaded as $uploadedFile) {
                        $file = new File();
                        $file->fromPost($uploadedFile);
                        /** @noinspection PhpUndefinedFieldInspection */
                        $file->description = $field->label;
                        $file->save();

                        $submission->uploaded_files()->add($file);
                    }
                } else {
                    $file = new File();
                    $file->fromPost($uploaded);
                    /** @noinspection PhpUndefinedFieldInspection */
                    $file->description = $field->label;
                    $file->save();

                    $submission->uploaded_files()->add($file);
                }
            }

            return $submission;
        });
    }

    /**
     * Sends form submissions notifications.
     *
     * @param Submission $submission
     */
    private function sendNotifications(Submission $submission): void
    {
        if (Settings::usesQueue()) {
            Queue::push(
                SendNotifiesJob::class,
                ['submission_id' => $submission->id]
            );
        } else {
            Notify::make()->execute($submission);
        }
    }
}
