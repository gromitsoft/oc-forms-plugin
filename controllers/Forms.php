<?php namespace GromIT\Forms\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Behaviors\RelationController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use Backend\Widgets\Form as FormWidget;
use GromIT\Forms\Actions\Forms\Copy;
use GromIT\Forms\Actions\Forms\Export;
use GromIT\Forms\Actions\Forms\Import;
use GromIT\Forms\Classes\Permissions;
use GromIT\Forms\Models\Field;
use GromIT\Forms\Models\Form;
use GromIT\Forms\Models\Form as FormModel;
use GromIT\Forms\Models\UploadForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use October\Rain\Database\Model;
use October\Rain\Database\Models\DeferredBinding;
use October\Rain\Exception\ValidationException;
use October\Rain\Support\Facades\Flash;
use October\Rain\Support\Facades\Yaml;
use System\Models\File;
use Throwable;

/**
 * Forms Back-end Controller
 */
class Forms extends Controller
{
    public $implement = [
        ListController::class,
        FormController::class,
        RelationController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    protected $requiredPermissions = [Permissions::EDIT_FORMS];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Gromit.Forms', 'forms', 'forms');

        if ($this->action === 'index' && request()->ajax()) {
            $this->bindUploadForm();
        }
    }

    /**
     * Popup for select json with form config.
     *
     * @return false|mixed
     */
    public function index_onShowUploadFormPopup()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->makePartial('$/gromit/forms/partials/popups/_popup_form.htm', [
            'form'    => $this->widget->uploadFormForm,
            'action'  => 'onUploadForm',
            'title'   => __('gromit.forms::lang.controllers.forms.upload_form_title'),
            'btnText' => __('gromit.forms::lang.controllers.forms.upload_form_btn_text'),
            'btnIcon' => 'oc-icon-upload'
        ]);
    }

    /**
     * Imports form from json.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \October\Rain\Exception\ValidationException
     * @throws \Throwable
     */
    public function index_onUploadForm(): RedirectResponse
    {
        $defer = DeferredBinding::query()
            ->where('master_type', UploadForm::class)
            ->where('slave_type', File::class)
            ->where('session_key', post('_session_key'))
            ->first();

        if ($defer === null) {
            Flash::error('Ошибка загрузки');
            return redirect($this->actionUrl('/'));
        }

        /** @var File $file */
        $file = File::query()->find($defer->slave_id);

        $defer->delete();

        if (strtolower($file->getExtension()) !== 'json') {
            $file->delete();
            throw new ValidationException([
                'file' => __('gromit.forms::lang.controllers.forms.upload_must_be_json')
            ]);
        }

        $newForm = Import::make()->execute($file);

        $file->delete();

        return redirect($this->actionUrl('update', $newForm->id));
    }

    private function bindUploadForm(): void
    {
        $this->makeWidget(FormWidget::class, [
            'alias'  => 'uploadFormForm',
            'model'  => new UploadForm(),
            'fields' => [
                'file' => [
                    'label'     => __('gromit.forms::lang.controllers.forms.upload_file_field_label'),
                    'comment'   => __('gromit.forms::lang.controllers.forms.upload_file_field_comment'),
                    'type'      => 'fileupload',
                    'mode'      => 'file',
                    'fileTypes' => 'json',
                ]
            ]
        ])->bindToController();
    }

    /**
     * Copy form popup.
     *
     * @param int $formId
     *
     * @return string
     * @throws \October\Rain\Exception\ApplicationException
     */
    public function update_onShowCopyPopup(int $formId): string
    {
        /**
         * @var self|FormController $this
         * @var FormModel           $form
         */
        $formModel = $this->formFindModelObject($formId);

        $formWidget = new FormWidget($this, [
            'model'  => $formModel,
            'fields' => [
                'name' => [
                    'label'   => __('gromit.forms::lang.controllers.forms.copy_name_field_label'),
                    'comment' => __('gromit.forms::lang.controllers.forms.copy_name_field_comment'),
                ],
                'key'  => [
                    'label'   => __('gromit.forms::lang.controllers.forms.copy_key_field_label'),
                    'comment' => __('gromit.forms::lang.controllers.forms.copy_key_field_comment'),
                    'preset'  => 'name'
                ]
            ]
        ]);

        return $this->makePartial('$/gromit/forms/partials/popups/_popup_form.htm', [
            'title'   => __('gromit.forms::lang.controllers.forms.copy_form_title'),
            'form'    => $formWidget,
            'action'  => 'onCopy',
            'btnText' => __('gromit.forms::lang.controllers.forms.copy_form_btn_text'),
            'btnIcon' => 'oc-icon-copy'
        ]);
    }

    /**
     * Creates form duplicate.
     *
     * @param int $formId
     *
     * @return RedirectResponse|Redirector
     * @throws Throwable
     */
    public function update_onCopy(int $formId)
    {
        /**
         * @var self|FormController $this
         * @var FormModel           $form
         */
        $form = $this->formFindModelObject($formId);

        $newForm = Copy::make()->execute(
            $form,
            post('name'),
            post('key')
        );

        return redirect($this->actionUrl('update', $newForm->id));
    }

    /**
     * Adds fields to field config popup.
     *
     * @param FormWidget                   $widget
     * @param string                       $field
     * @param \October\Rain\Database\Model $model
     */
    public function relationExtendManageWidget(FormWidget $widget, string $field, Model $model): void
    {
        if ($model instanceof Form && $field === 'fields') {
            $widget->bindEvent('form.extendFieldsBefore', function () use ($widget) {
                /** @var Field $formModel */
                $formModel = $widget->model;

                if (!$formModel->id) {
                    $fieldType       = post('field_type') ?: post('Field.type');
                    $formModel->type = $fieldType;
                }

                if ($formModel->type === Field::TYPE_RECAPTCHA) {
                    $widget->fields['is_required']['default']      = true;
                    $widget->fields['is_required']['disabled']     = true;
                    $widget->fields['required_message']['default'] =
                        __('gromit.forms::lang.validation.captcha.confirm');
                }
            });

            $widget->bindEvent('form.extendFields', function () use ($widget) {
                /** @var Field $formModel */
                $formModel = $widget->model;

                if ($formModel->id) {
                    $fieldType = $formModel->type;
                } else {
                    $fieldType       = post('field_type') ?: post('Field.type');
                    $formModel->type = $fieldType;
                }

                $extraFieldsPath = plugins_path('gromit/forms/models/field/bytype/' . $fieldType . '.yaml');

                if (file_exists($extraFieldsPath)) {
                    $widget->addFields(
                        Yaml::parseFile($extraFieldsPath)
                    );
                }
            });
        }
    }

    /**
     * Exports form to json and returns response with file.
     *
     * @param int $formId
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function download(int $formId)
    {
        /** @var Form $form */
        $form = Form::query()->with('fields')->find($formId);

        if ($form === null) {
            Flash::error(__('gromit.forms::lang.controllers.forms.form_not_found'));

            return redirect($this->actionUrl('/'));
        }

        $json = Export::make()->execute($form);

        return response($json, 200, [
            'Content-type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $form->key . '.form.json"'
        ]);
    }
}
