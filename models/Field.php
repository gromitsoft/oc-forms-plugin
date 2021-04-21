<?php namespace GromIT\Forms\Models;

use LogicException;
use October\Rain\Argon\Argon;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use October\Rain\Database\Relations\BelongsTo;
use October\Rain\Database\Traits\Nullable;
use October\Rain\Database\Traits\Sortable;
use October\Rain\Database\Traits\Validation;
use October\Rain\Exception\ValidationException;
use October\Rain\Support\Arr;
use October\Rain\Support\Str;
use System\Models\MailTemplate;

/**
 * Field Model
 *
 * @property int    $id
 * @property int    $form_id
 * @property string $label
 * @property string $key
 * @property string $comment
 * @property string $type
 * @property mixed  $default
 * @property bool   $is_required
 * @property string $required_message
 * @property string $wrapper_class
 * @property string $field_class
 * @property array  $options
 * @property Argon  $created_at
 * @property Argon  $updated_at
 *
 * @property Form   $form
 * @method BelongsTo form()
 *
 * @method static self|Builder query()
 */
class Field extends Model
{
    #region Base
    public $table = 'gromit_forms_fields';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'is_required' => 'bool'
    ];

    protected $jsonable = [
        'options'
    ];
    #endregion

    #region Constants
    public const TYPE_STRING = 'string';
    public const TYPE_EMAIL = 'email';
    public const TYPE_NUMBER = 'number';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_SELECT = 'select';
    public const TYPE_RADIO = 'radio';
    public const TYPE_CHECKBOX_LIST = 'checkbox_list';
    public const TYPE_UPLOAD = 'upload';
    public const TYPE_RECAPTCHA = 'recaptcha';
    #endregion

    #region Relations
    public $belongsTo = [
        'form' => Form::class
    ];
    #endregion

    #region Traits
    use Sortable;

    use Nullable;

    protected $nullable = [
        'options',
        'default',
        'comment',
        'required_message',
        'wrapper_class',
        'field_class',
    ];

    use Validation;

    public $rules = [
        'form_id' => 'required|exists:gromit_forms_forms,id',
        'label'   => 'required',
        'key'     => 'required',
        'type'    => 'required',
    ];

    public $customMessages = [];
    #endregion

    #region Events
    public function filterFields($fields): void
    {
        if (isset($fields->default)) {
            if ($this->type === self::TYPE_CHECKBOX) {
                $fields->default->type  = 'checkbox';
                $fields->default->label = __('gromit.forms::lang.models.field.defaults.checkbox');
            } else {
                $fields->default->type  = 'text';
                $fields->default->label = __('gromit.forms::lang.models.field.defaults.others');
            }
        }
    }

    protected function beforeValidate(): void
    {
        if ($this->type === self::TYPE_RECAPTCHA) {
            $this->is_required = true;
        }

        $this->customMessages = [
            'form_id.required' => __('gromit.forms::lang.models.field.validation.form_id.required'),
            'form_id.exists'   => __('gromit.forms::lang.models.field.validation.form_id.exists'),
            'label.required'   => __('gromit.forms::lang.models.field.validation.label.required'),
            'key.required'     => __('gromit.forms::lang.models.field.validation.key.required'),
            'key.exists'       => __('gromit.forms::lang.models.field.validation.key.exists'),
            'type.required'    => __('gromit.forms::lang.models.field.validation.type.required'),
        ];
    }

    /**
     * @throws ValidationException
     */
    protected function afterValidate(): void
    {
        $exists = self::query()
            ->where('id', '!=', $this->id)
            ->where('key', $this->key)
            ->where('form_id', $this->form_id)
            ->exists();

        if ($exists) {
            throw new ValidationException(['key' => __('gromit.forms::lang.models.field.validation.key.exists')]);
        }
    }
    #endregion

    #region Options
    public function getMailTemplateOptions(): array
    {
        return MailTemplate::listAllTemplates();
    }
    #endregion

    #region Methods
    public static function getTypeIcon(string $type, bool $ocPrefix = false): string
    {
        $icons = [
            self::TYPE_STRING        => 'icon-i-cursor',
            self::TYPE_EMAIL         => 'icon-at',
            self::TYPE_NUMBER        => 'icon-plus-square',
            self::TYPE_TEXTAREA      => 'icon-i-cursor',
            self::TYPE_CHECKBOX      => 'icon-check-square-o',
            self::TYPE_SELECT        => 'icon-caret-square-o-down',
            self::TYPE_RADIO         => 'icon-dot-circle-o',
            self::TYPE_CHECKBOX_LIST => 'icon-check-square-o',
            self::TYPE_UPLOAD        => 'icon-upload',
            self::TYPE_RECAPTCHA     => 'icon-ban',
        ];

        $icon = Arr::get($icons, $type);

        if (!$icon) {
            return '';
        }

        return $ocPrefix ? 'oc-' . $icon : $icon;
    }

    public function getIcon(bool $ocPrefix = false): string
    {
        return self::getTypeIcon($this->type, $ocPrefix);
    }

    public static function getTypesList(): array
    {
        return [
            self::TYPE_STRING,
            self::TYPE_EMAIL,
            self::TYPE_NUMBER,
            self::TYPE_TEXTAREA,
            self::TYPE_CHECKBOX,
            self::TYPE_SELECT,
            self::TYPE_RADIO,
            self::TYPE_CHECKBOX_LIST,
            self::TYPE_UPLOAD,
            self::TYPE_RECAPTCHA,
        ];
    }

    public static function getFieldTypeName(string $type)
    {
        return __("gromit.forms::lang.models.field.type.{$type}");
    }

    public function getTypeName()
    {
        return self::getFieldTypeName($this->type);
    }

    /**
     * Options list (for select, radio, checkbox_list).
     *
     * @return array
     */
    public function getOptions(): array
    {
        return collect($this->options)
            ->mapWithKeys(function ($option) {
                return [$this->getOptionKey($option) => $option['option']];
            })
            ->all();
    }

    private function getOptionKey(array $option): string
    {
        return Arr::get($option, 'key') ?: Str::slug($option['option']);
    }

    /**
     * Field option value by key (for select, radio, checkbox_list).
     *
     * @param $key
     *
     * @return string
     */
    public function getOptionValue($key): string
    {
        $options = $this->getOptions();

        if (!is_array($options) || count($options) === 0) {
            return '';
        }

        return Arr::get($options, $key);
    }

    public function getValidationRules(): array
    {
        $rules = [];

        if ($this->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        switch ($this->type) {
            case self::TYPE_EMAIL:
                $rules[] = 'email';
                break;
            case self::TYPE_CHECKBOX_LIST:
                $rules[] = 'array';
                break;
            case self::TYPE_RADIO:
            case self::TYPE_SELECT:
                if (!empty($this->getOptions())) {
                    $rules[] = 'in:' . implode(',', array_keys($this->getOptions()));
                }
                break;
            case self::TYPE_UPLOAD:
                if (is_array($this->options)) {
                    $mimes = Arr::get($this->options, 'mimes');

                    if (trim($mimes) !== '') {
                        $mimes = collect(explode(',', $mimes));

                        if ($mimes->isNotEmpty()) {
                            $mimes = $mimes->map(function ($mime) {
                                return trim($mime);
                            });

                            $rules[] = 'mimes:' . $mimes->implode(',');
                        }
                    }

                    if ($max = Arr::get($this->options, 'max')) {
                        $rules[] = 'max:' . $max;
                    }
                }
                break;
        }

        return $rules;
    }

    public function getValidationMessages(): array
    {
        $messages = [];

        if ($this->is_required) {
            switch ($this->type) {
                case self::TYPE_STRING:
                case self::TYPE_EMAIL:
                case self::TYPE_NUMBER:
                case self::TYPE_TEXTAREA:
                    $messages[$this->key . '.required'] = $this->required_message
                        ?: __('gromit.forms::lang.models.field.dynamic_validation.required_string', [
                            'label' => $this->label,
                        ]);
                    break;
                case self::TYPE_CHECKBOX:
                    $messages[$this->key . '.required'] = $this->required_message
                        ?: __('gromit.forms::lang.models.field.dynamic_validation.required_checkbox', [
                            'label' => $this->label,
                        ]);
                    break;
                case self::TYPE_SELECT:
                case self::TYPE_RADIO:
                case self::TYPE_CHECKBOX_LIST:
                    $messages[$this->key . '.required'] = $this->required_message
                        ?: __('gromit.forms::lang.models.field.dynamic_validation.required_select', [
                            'label' => $this->label,
                        ]);
                    break;
                case self::TYPE_UPLOAD:
                    $messages[$this->key . '.required'] = $this->required_message
                        ?: __('gromit.forms::lang.models.field.dynamic_validation.required_upload');
                    break;
            }
        }

        switch ($this->type) {
            case self::TYPE_EMAIL:
                $messages[$this->key . '.email'] = __('gromit.forms::lang.models.field.dynamic_validation.email', [
                    'label' => $this->label,
                ]);
                break;
            case self::TYPE_SELECT:
            case self::TYPE_RADIO:
            case self::TYPE_CHECKBOX_LIST:
                $messages[$this->key . '.in'] = __('gromit.forms::lang.models.field.dynamic_validation.in', [
                    'label' => $this->label,
                ]);
                break;
            case self::TYPE_UPLOAD:
                $mimes   = Arr::get($this->options, 'mimes');
                $maxSize = Arr::get($this->options, 'max');

                $mimesMessage   = __('gromit.forms::lang.models.field.dynamic_validation.upload_mimes', [
                    'mimes' => $mimes,
                ]);
                $maxSizeMessage = __('gromit.forms::lang.models.field.dynamic_validation.upload_max_size', [
                    'maxSize' => $maxSize,
                ]);

                $messages[$this->key . '.mimes'] = $mimesMessage;
                $messages[$this->key . '.max']   = $maxSizeMessage;
                break;
        }

        return $messages;
    }

    /**
     * Is there mime type restrictions?
     * Only for uploads.
     *
     * @return bool
     * @throws LogicException
     */
    public function uploadHasMimes(): bool
    {
        if ($this->type !== self::TYPE_UPLOAD) {
            throw new LogicException(__('gromit.forms::lang.models.field.only_for_upload'));
        }

        $mimes = Arr::get($this->options, 'mimes');

        if (trim($mimes) === '') {
            return false;
        }

        $mimes = explode(',', $mimes);

        return count($mimes) > 0;
    }

    /**
     * Access attribute value for upload input.
     * Only for uploads.
     *
     * @return string
     * @throws LogicException
     */
    public function uploadAcceptHtmlAttribute(): string
    {
        if ($this->type !== self::TYPE_UPLOAD) {
            throw new LogicException(__('gromit.forms::lang.models.field.only_for_upload'));
        }

        $mimes = Arr::get($this->options, 'mimes');

        if (trim($mimes) !== '') {
            return '';
        }

        $mimes = explode(',', $mimes);

        $result = [];

        foreach ($mimes as $mime) {
            $result[] = '.' . $mime;
        }

        return implode(',', $result);
    }

    /**
     * Is upload field allows multiple files.
     * Only for uploads.
     *
     * @return bool
     * @throw LogicException
     */
    public function uploadIsMultiple(): bool
    {
        if ($this->type !== self::TYPE_UPLOAD) {
            throw new LogicException(__('gromit.forms::lang.models.field.only_for_upload'));
        }

        return (bool)Arr::get($this->options, 'multiple');
    }

    /**
     * Send notifications to entered email?
     * Only for emails.
     *
     * @return bool
     * @throws LogicException
     */
    public function emailNeedToSendNotify(): bool
    {
        if ($this->type !== self::TYPE_EMAIL) {
            throw new LogicException(__('gromit.forms::lang.models.field.only_for_email'));
        }

        return (bool)Arr::get($this->options, 'send_notify');
    }

    /**
     * Mail template for user notifications.
     * Only for emails.
     *
     * @return string
     */
    public function emailGetNotifyTemplate(): string
    {
        if ($this->type !== self::TYPE_EMAIL) {
            throw new LogicException(__('gromit.forms::lang.models.field.only_for_email'));
        }

        return Arr::get($this->options, 'mail_template');
    }
    #endregion
}
