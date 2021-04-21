<?php namespace GromIT\Forms\Models;

use October\Rain\Argon\Argon;
use October\Rain\Database\Builder;
use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use October\Rain\Database\Relations\AttachMany;
use October\Rain\Database\Relations\BelongsTo;
use October\Rain\Database\Traits\Nullable;
use October\Rain\Database\Traits\Validation;
use System\Models\File;

/**
 * Submission Model
 *
 * @property int               $id
 * @property int               $form_id
 * @property array             $data
 * @property array|null        $request_data
 * @property array|null        $user_data
 * @property Argon|null        $viewed_at
 * @property Argon             $created_at
 * @property Argon             $updated_at
 *
 * @property Form              $form
 * @method BelongsTo           form()
 *
 * @property Collection|File[] $uploaded_files
 * @method AttachMany          uploaded_files()
 *
 * @method static self|Builder query()
 * @method static self|Builder whereFormId(int $formId)
 */
class Submission extends Model
{
    #region Base
    public $table = 'gromit_forms_submissions';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $fillable = [
        'form_id',
        'data',
        'request_data',
        'user_data',
    ];

    protected $dates = [
        'viewed_at',
    ];

    protected $jsonable = [
        'data',
        'request_data',
        'user_data',
    ];
    #endregion

    #region Relations
    public $belongsTo = [
        'form' => Form::class
    ];

    public $attachMany = [
        'uploaded_files' => File::class
    ];
    #endregion

    #region Traits
    use Nullable;

    protected $nullable = ['viewed_at'];

    use Validation;

    public $rules = [
        'form_id'   => 'required|exists:gromit_forms_forms,id',
        'data'      => 'required|array',
        'viewed_at' => 'nullable|date',
    ];
    public $customMessages = [];
    #endregion

    #region Events
    protected function beforeValidate(): void
    {
        $this->customMessages = [
            'form_id.required' => __('gromit.forms::lang.models.submission.validation.form_id.required'),
            'form_id.exists'   => __('gromit.forms::lang.models.submission.validation.form_id.exists'),
            'data.required'    => __('gromit.forms::lang.models.submission.validation.data.required'),
            'data.array'       => __('gromit.forms::lang.models.submission.validation.data.array'),
            'viewed_at.date'   => __('gromit.forms::lang.models.submission.validation.viewed_at.date'),
        ];
    }
    #endregion

    #region Methods
    public function getSubmissionData(): array
    {
        $data = [];

        foreach ($this->data as $fieldKey => $fieldValue) {
            /** @var Field $field */
            $field = $this->form->fields()
                ->where('key', $fieldKey)
                ->first();

            switch ($field->type) {
                case Field::TYPE_STRING:
                case Field::TYPE_EMAIL:
                case Field::TYPE_NUMBER:
                case Field::TYPE_TEXTAREA:
                    $data[$field->label] = $fieldValue;
                    break;
                case Field::TYPE_CHECKBOX:
                    $data[$field->label] = $fieldValue
                        ? __('gromit.forms::lang.messages.yes')
                        : __('gromit.forms::lang.messages.no');
                    break;
                case Field::TYPE_SELECT:
                case Field::TYPE_RADIO:
                    if (!empty($field->getOptions())) {
                        $data[$field->label] = $field->getOptionValue($fieldValue);
                    } else {
                        $data[$field->label] = $fieldValue;
                    }
                    break;
                case Field::TYPE_CHECKBOX_LIST:
                    $data[$field->label] = collect($fieldValue)
                        ->map(function ($fv) use ($field) {
                            return $field->getOptionValue($fv);
                        })
                        ->implode(', ');
                    break;
            }
        }

        return $data;
    }

    public function markAsViewed(): void
    {
        if (empty($this->viewed_at)) {
            $this->viewed_at = now();
            $this->forceSave();
        }
    }
    #endregion
}
