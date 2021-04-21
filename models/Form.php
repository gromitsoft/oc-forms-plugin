<?php namespace GromIT\Forms\Models;

use Backend\Facades\Backend;
use October\Rain\Argon\Argon;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use October\Rain\Database\Relations\HasMany;
use October\Rain\Database\Traits\Nullable;
use October\Rain\Database\Traits\Validation;
use October\Rain\Support\Arr;
use October\Rain\Support\Collection;
use stdClass;
use System\Models\MailTemplate;

/**
 * Form Model
 *
 * @property int                $id
 * @property string             $name
 * @property string             $key
 * @property string             $description
 * @property string             $wrapper_class
 * @property string             $form_class
 * @property array              $buttons_config
 * @property string             $success_title
 * @property string             $success_msg
 * @property array              $extra_emails
 * @property string             $mail_template
 * @property bool               $is_active
 * @property Argon              $created_at
 * @property Argon              $updated_at
 *
 * @property Collection|Field[] $fields
 * @method HasMany              fields()
 *
 * @method static self|Builder query()
 * @method static self|Builder whereFormKey(string $formKey)
 */
class Form extends Model
{
    #region Base
    public $table = 'gromit_forms_forms';
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'is_active' => 'bool'
    ];

    protected $jsonable = [
        'extra_emails',
        'buttons_config',
    ];
    #endregion

    #region Relations
    public $hasMany = [
        'fields' => Field::class
    ];
    #endregion

    #region Traits
    use Nullable;

    protected $nullable = [
        'description',
        'wrapper_class',
        'form_class',
        'success_title',
        'success_msg',
        'extra_emails',
        'mail_template',
    ];

    use Validation;

    public $rules = [
        'name'                 => 'required',
        'key'                  => 'required|unique:gromit_forms_forms|alpha_dash',
        'extra_emails'         => 'nullable|array',
        'extra_emails.*.email' => 'required|email'
    ];
    public $customMessages = [];
    #endregion

    #region Events
    public function filterFields(stdClass $fields, ?string $context = null): void
    {
        if ($context === 'update') {
            $url   = Backend::url('system/settings/update/gromit/forms/settings');
            $label = __('gromit.forms::lang.models.form.fields.mail_template.comment_link_label');

            $fields->mail_template->comment = __('gromit.forms::lang.models.form.fields.mail_template.comment', [
                'link' => "<a href='$url' target='_blank'>$label</a>"
            ]);
        }
    }

    protected function beforeValidate(): void
    {
        $this->customMessages = [
            'name.required'                 => __('gromit.forms::lang.models.form.validation.name.required'),
            'key.required'                  => __('gromit.forms::lang.models.form.validation.key.required'),
            'key.unique'                    => __('gromit.forms::lang.models.form.validation.key.unique'),
            'key.alpha_dash'                => __('gromit.forms::lang.models.form.validation.key.alpha_dash'),
            'extra_emails.*.email.required' =>
                __('gromit.forms::lang.models.form.validation.extra_emails.email.required'),
            'extra_emails.*.email.email'    => __('gromit.forms::lang.models.form.validation.extra_emails.email.email'),
        ];
    }
    #endregion

    #region Options
    public function getMailTemplateOptions(): array
    {
        return MailTemplate::listAllTemplates();
    }
    #endregion

    #region Scopes
    public function scopeWhereFormKey(Builder $query, string $formKey): void
    {
        $query->where('key', $formKey);
    }
    #endregion

    #region Methods
    /**
     * Additions email addresses for notify.
     *
     * @return array
     */
    public function getExtraEmailAddresses(): array
    {
        if (empty($this->extra_emails)) {
            return [];
        }

        return collect($this->extra_emails)
            ->map(function ($email) {
                return trim($email['email']);
            })
            ->all();
    }

    public function getValidationRules(): array
    {
        return $this->fields->reduce(function (array $rules, Field $field) {
            if ($field->type === Field::TYPE_RECAPTCHA) {
                return $rules;
            }

            return array_merge($rules, [
                $field->key => implode('|', $field->getValidationRules())
            ]);
        }, []);
    }

    public function getValidationMessages(): array
    {
        return $this->fields->reduce(function (array $messages, Field $field) {
            return array_merge($messages, $field->getValidationMessages());
        }, []);
    }

    public function hasUploadField(): bool
    {
        return $this
            ->fields()
            ->where('type', Field::TYPE_UPLOAD)
            ->exists();
    }

    public function hasRecaptchaField(): bool
    {
        return $this
            ->fields()
            ->where('type', Field::TYPE_RECAPTCHA)
            ->exists();
    }

    public function getRecaptchaField(): ?Field
    {
        /** @var \GromIT\Forms\Models\Field|null $recaptchaField */
        $recaptchaField = $this
            ->fields()
            ->where('type', Field::TYPE_RECAPTCHA)
            ->first();

        return $recaptchaField;
    }

    /**
     * @return \GromIT\Forms\Models\Field[]
     */
    public function getUserNotifyFields(): array
    {
        return $this
            ->fields()
            ->where('type', Field::TYPE_EMAIL)
            ->get()
            ->reduce(function (array $acc, Field $field) {
                if ($field->emailNeedToSendNotify()) {
                    $acc[] = $field;
                }

                return $acc;
            }, []);
    }

    public function getSubmitBtnLabel(): string
    {
        if (!is_array($this->buttons_config)) {
            return 'Submit';
        }

        return Arr::get($this->buttons_config, 'submit_label') ?: 'Submit';
    }

    public function getSubmitBtnClass(): string
    {
        if (!is_array($this->buttons_config)) {
            return '';
        }

        return Arr::get($this->buttons_config, 'submit_class') ?: '';
    }

    public function isClearBtnVisible(): bool
    {
        if (!is_array($this->buttons_config)) {
            return true;
        }

        return (bool)Arr::get($this->buttons_config, 'clear_visible');
    }

    public function getClearBtnLabel()
    {
        if (!is_array($this->buttons_config)) {
            return 'Clear';
        }

        return Arr::get($this->buttons_config, 'clear_label') ?: 'Clear';
    }

    public function getClearBtnClass()
    {
        if (!is_array($this->buttons_config)) {
            return '';
        }

        return Arr::get($this->buttons_config, 'clear_class') ?: '';
    }
    #endregion
}
