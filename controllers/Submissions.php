<?php namespace GromIT\Forms\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use Backend\Widgets\Form as FormWidget;
use GromIT\Forms\Actions\Submissions\Export;
use GromIT\Forms\Classes\Permissions;
use GromIT\Forms\Models\Form as FormModel;
use GromIT\Forms\Models\Submission;
use League\Csv\CannotInsertRecord;
use October\Rain\Database\Model;
use October\Rain\Support\Facades\Flash;

/**
 * Submissions Back-end Controller
 *
 * @mixin FormController
 * @mixin ListController
 */
class Submissions extends Controller
{
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $hiddenActions = ['create', 'update'];

    protected $requiredPermissions = [Permissions::SHOW_SUBMISSIONS];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Gromit.Forms', 'forms', 'submissions');
    }

    /**
     * @return mixed|void
     */
    public function index_onDelete()
    {
        if (!$this->user->hasAccess(Permissions::DELETE_SUBMISSIONS)) {
            Flash::error(__('gromit.forms::lang.messages.access_denied'));
            return;
        }

        return $this->asExtension(ListController::class)->index_onDelete();
    }

    public function index_onShowExportPopup()
    {
        if (!$this->user->hasAccess(Permissions::DELETE_SUBMISSIONS)) {
            return $this->makePartial('$/gromit/forms/partials/popups/_popup_msg.htm', [
                'type' => 'error',
                'msg'  => __('gromit.forms::lang.messages.access_denied')
            ]);
        }

        $formsList = FormModel::query()->pluck('name', 'id');

        if ($formsList->isEmpty()) {
            return $this->makePartial('$/gromit/forms/partials/popups/_popup_msg.htm', [
                'type' => 'error',
                'msg'  => __('gromit.forms::lang.messages.no_forms')
            ]);
        }

        $formModel = new Model();
        $formModel->addDynamicMethod('getFormIdOptions', function () {
            return FormModel::query()->pluck('name', 'id')->all();
        });

        $form = $this->makeWidget(FormWidget::class, [
            'model'  => $formModel,
            'fields' => [
                'form_id' => [
                    'label'   => __('gromit.forms::lang.controllers.submissions.export.form_id.label'),
                    'comment' => __('gromit.forms::lang.controllers.submissions.export.form_id.comment'),
                    'type'    => 'dropdown',
                ]
            ]
        ]);

        return $this->makePartial('export_form', [
            'form' => $form
        ]);
    }

    public function export()
    {
        if (strtolower(request()->method()) !== 'post') {
            return redirect($this->actionUrl('/'));
        }

        if (!$this->user->hasAccess(Permissions::EXPORT_SUBMISSIONS)) {
            Flash::error(__('gromit.forms::lang.messages.access_denied'));
            return redirect($this->actionUrl('/'));
        }

        $formId = post('form_id');

        $validation = validator([
            'form_id' => $formId,
        ], [
            'form_id' => 'required|exists:gromit_forms_forms,id',
        ], [
            'form_id.required' => __('gromit.forms::lang.controllers.submissions.export.validation.form_id.required'),
            'form_id.exists'   => __('gromit.forms::lang.controllers.submissions.export.validation.form_id.exists'),
        ]);

        if ($validation->fails()) {
            Flash::error($validation->getMessageBag()->first());
            return redirect($this->actionUrl('/'));
        }

        try {
            $csv = Export::make()->execute($formId);

            $form = FormModel::query()->find($formId);

            $csv->output($form->name . '.csv');
            die;
        } catch (CannotInsertRecord $e) {
            Flash::error($e->getMessage());
            return redirect($this->actionUrl('/'));
        }
    }

    /**
     * @param int $submissionId
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function preview_onDelete(int $submissionId)
    {
        if (!$this->user->hasAccess(Permissions::DELETE_SUBMISSIONS)) {
            Flash::error(__('gromit.forms::lang.messages.access_denied'));
            return;
        }

        Submission::destroy($submissionId);

        Flash::success(__('gromit.forms::lang.messages.deleted'));

        return redirect($this->actionUrl('/'));
    }

    public function preview(int $recordId = null): void
    {
        $this->asExtension(FormController::class)->preview($recordId);

        if ($this->fatalError) {
            return;
        }

        /** @var Submission $submission */
        $submission = Submission::query()->find($recordId);
        $submission->markAsViewed();
    }
}
