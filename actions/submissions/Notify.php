<?php

namespace GromIT\Forms\Actions\Submissions;

use Backend\Facades\Backend;
use Exception;
use GromIT\Forms\Models\Settings;
use GromIT\Forms\Models\Submission;
use GromIT\Forms\Traits\SelfMakeable;
use Illuminate\Mail\Message;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Notify
{
    use SelfMakeable;

    /**
     * @var \GromIT\Forms\Models\Submission
     */
    private $submission;

    /**
     * Sends notifications.
     *
     * @param \GromIT\Forms\Models\Submission $submission
     */
    public function execute(Submission $submission): void
    {
        $this->submission = $submission;

        $this->sendToAdmins();
        $this->sendToUsers();
    }

    /**
     * Sends notification to site admins.
     */
    private function sendToAdmins(): void
    {
        $template = $this->submission->form->mail_template ?: Settings::getMailTemplate();
        if (empty($template)) {
            return;
        }

        $emails = array_merge(Settings::getEmails(), $this->submission->form->getExtraEmailAddresses());

        if (count($emails) === 0) {
            return;
        }

        try {
            $appUrl = config('app.url');
            $backendUri = Backend::uri();

            $data = [
                'title'    => $this->submission->form->name,
                'date'     => $this->submission->created_at->format('d.m.Y H:i'),
                'hasFiles' => $this->submission->uploaded_files()->exists(),
                'url'      => "{$appUrl}/{$backendUri}/gromit/forms/submissions/preview/7",
                'data'     => $this->submission->getSubmissionData(),
            ];

            Mail::send($template, $data, function (Message $message) use ($emails) {
                $message->to($emails);
            });
        } catch (Exception $ex) {
            Log::error('Notify error');
            Log::error($ex);
        }
    }

    /**
     * Sends notifications to users.
     */
    private function sendToUsers(): void
    {
        $emailFieldsWithNotify = $this->submission->form->getUserNotifyFields();

        if (count($emailFieldsWithNotify) === 0) {
            return;
        }

        $sendTo = [];

        foreach ($emailFieldsWithNotify as $field) {
            $email = Arr::get($this->submission->data, $field->key);

            if (empty($email)) {
                continue;
            }

            $template = $field->emailGetNotifyTemplate();

            if (empty($template)) {
                continue;
            }

            $sendTo[$email] = $template;
        }

        if (count($sendTo) === 0) {
            return;
        }

        foreach ($sendTo as $email => $template) {
            try {
                $data = [
                    'title'    => $this->submission->form->name,
                    'date'     => $this->submission->created_at->format('d.m.Y H:u'),
                    'hasFiles' => $this->submission->uploaded_files()->exists(),
                    'data'     => $this->submission->getSubmissionData(),
                ];

                Mail::send($template, $data, function (Message $message) use ($email) {
                    $message->to($email);
                });
            } catch (Exception $ex) {
                Log::error('Notify user error');
                Log::error($ex);
            }
        }
    }
}
