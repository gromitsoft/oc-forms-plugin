<?php

use GromIT\Forms\Models\Field;

return [
    'actions'     => [
        'forms'       => [
            'import' => [
                'validate_file_content' => 'Uploaded file is not form config',
            ],
        ],
        'submissions' => [
            'create' => [
                'form_is_disabled'  => 'Form is disabled, submissions not accepting',
                'recaptcha_confirm' => '',
                'field_not_found'   => 'Field :key is not found'
            ]
        ]
    ],
    'permissions' => [
        'tab'                => 'Forms',
        'edit_forms'         => 'Edit forms',
        'edit_settings'      => 'Edit forms notifications settings',
        'show_submissions'   => 'View forms submissions',
        'export_submissions' => 'Export forms submissions',
        'delete_submissions' => 'Delete forms submissions',
    ],
    'components'  => [
        'forms' => [
            'details'            => [
                'name'        => 'Form',
                'description' => 'Renders form on page',
            ],
            'properties'         => [
                'form_key'   => [
                    'title'       => 'Form',
                    'description' => 'Choose form for render',
                ],
                'add_styles' => [
                    'title'       => 'Add styles',
                    'description' => 'Add default css styles',
                ],
            ],
            'form_is_not_active' => 'form is not active',
            'unknown_form'       => 'Unknown form',
        ]
    ],
    'controllers' => [
        'fields'      => [
            'recaptcha_comment_1' => 'For this field you must enter you keys in',
            'recaptcha_comment_2' => 'forms settings',
            'back_to_form'        => 'Back to form',
            'reorder'             => [
                'title' => 'Sort fields',
            ]
        ],
        'forms'       => [
            'add_fields_after_create_msg' => 'You will can add fields after form creation',
            'breadcrumbs_root'            => 'Forms',
            'return_to_forms_list'        => 'Back to forms list',
            'record_name'                 => 'Form',

            'create_page_title' => 'New form',
            'update_page_title' => 'Edit form',
            'list_page_title'   => 'Forms',

            'form_not_found' => 'Form not found',

            'relation_fields_label'        => 'Field',
            'relation_fields_manage_title' => 'Field settings',

            'upload_form_title'         => 'Upload form',
            'upload_form_btn_text'      => 'Upload',
            'upload_must_be_json'       => 'File must have json extension',
            'upload_file_field_label'   => 'Form file',
            'upload_file_field_comment' => 'Choose file with form config',

            'copy_name_field_label'   => 'Form name',
            'copy_name_field_comment' => 'Form name for use in forms list',
            'copy_key_field_label'    => 'Form key',
            'copy_key_field_comment'  => 'Key for render form on site',
            'copy_form_title'         => 'Copy form',
            'copy_form_btn_text'      => 'Copy',

            'btn_add_field'                     => 'Add field',
            'btn_delete_field'                  => 'Delete field',
            'btn_delete_field_confirm'          => 'Are you sure?',
            'btn_reorder_fields'                => 'Sort',
            'btn_add_form'                      => 'Add form',
            'btn_delete_selected_forms'         => 'Delete selected',
            'btn_delete_selected_forms_confirm' =>
                'Delete selected forms? Forms submissions will be deleted as well!',
            'btn_upload_form'                   => 'Upload form',
            'btn_submissions'                   => 'Submissions',
            'btn_create'                        => 'Create',
            'btn_create_and_close'              => 'Create and close',
            'btn_create_load'                   => 'Creating',
            'btn_save'                          => 'Save',
            'btn_save_and_close'                => 'Save and close',
            'btn_save_load'                     => 'Saving',
            'btn_copy_form'                     => 'Copy form',
            'btn_export_form'                   => 'Export form',
            'btn_delete_confirm'                =>
                'Delete form? Form submissions will be deleted as well!',
            'btn_delete_load'                   => 'Deleting',
        ],
        'submissions' => [
            'breadcrumbs_root'           => 'Submissions',
            'files_not_uploaded'         => 'Files not uploaded',
            'return_tu_submissions_list' => 'Back to submissions',
            'filter_form'                => 'Form',
            'record_name'                => 'Submission',
            'preview_page_title'         => 'View submission',
            'list_page_title'            => 'Submissions',

            'export' => [
                'form_id'        => [
                    'label'   => 'Form',
                    'comment' => 'Choose form for export submissions',
                ],
                'popup_title'    => 'Export submissions',
                'popup_btn_text' => 'Export',
                'validation'     => [
                    'form_id.required' => 'Choose form',
                    'form_id.exists'   => 'Selected form not exists',
                ]
            ],

            'btn_export'                  => 'Export',
            'btn_back'                    => 'Back',
            'btn_back_to_forms'           => 'Back to forms list',
            'btn_delete_selected'         => 'Delete selected',
            'btn_delete_selected_confirm' => 'Delete selected submissions?',
            'btn_delete_confirm'          => 'Delete?',
            'btn_delete_load'             => 'Deleting',
        ],
    ],
    'models'      => [
        'field'      => [
            'columns'            => [
                'label'       => 'Field',
                'type'        => 'Type',
                'is_required' => 'Required',
                'sort_order'  => 'Sort fields',
            ],
            'fields'             => [
                'label'            => [
                    'label'   => 'Field label',
                    'comment' => 'Field label',
                ],
                'key'              => [
                    'label'   => 'Field key',
                    'comment' => 'Field key for retrieving field value',
                ],
                'comment'          => [
                    'label'   => 'Comment',
                    'comment' => 'Field hint',
                ],
                'is_required'      => [
                    'label' => 'Field is required',
                ],
                'required_message' => [
                    'label'   => 'Required message',
                    'comment' => 'Message user will see if field is empty',
                ],
                'wrapper_class'    => [
                    'label'   => 'Field wrapper class',
                    'comment' => 'CSS class for wrap field',
                ],
                'field_class'      => [
                    'label'   => 'Field class',
                    'comment' => 'CSS class for field',
                ],
            ],
            'validation'         => [
                'form_id' => [
                    'required' => 'Choose form',
                    'exists'   => 'Selected form is not exists',
                ],
                'label'   => [
                    'required' => 'Enter field name',
                ],
                'key'     => [
                    'required' => 'Enter field key',
                    'exists'   => 'Field with this key already exists',
                ],
                'type'    => [
                    'required' => 'Choose field type',
                ],
            ],
            'type'               => [
                Field::TYPE_STRING        => 'Text string',
                Field::TYPE_EMAIL         => 'Email address',
                Field::TYPE_NUMBER        => 'Numeric field',
                Field::TYPE_TEXTAREA      => 'Textarea',
                Field::TYPE_CHECKBOX      => 'Checkbox',
                Field::TYPE_SELECT        => 'Dropdown',
                Field::TYPE_RADIO         => 'Radio button',
                Field::TYPE_CHECKBOX_LIST => 'Checkbox list',
                Field::TYPE_UPLOAD        => 'Upload files',
                Field::TYPE_RECAPTCHA     => 'reCAPTCHA',
            ],
            'defaults'           => [
                'checkbox' => 'Checked by default',
                'others'   => 'Default value',
            ],
            'dynamic_validation' => [
                'required_string'   => 'Field :label is required',
                'required_checkbox' => 'You must check :label',
                'required_select'   => 'Select something in :label',
                'required_upload'   => 'Choose file',

                'email' => 'Enter correct email address in :label',
                'in'    => 'Choose correct value in :label',

                'upload_mimes'    => 'File type is not supported (Supported types: :mimes)',
                'upload_max_size' => 'Maximum file size is :maxSize KB',
            ],
            'only_for_upload'    => 'Only for files',
            'only_for_email'     => 'Only for email',
            'fields_by_type'     => [
                'select'    => [
                    'options' => [
                        'label'        => 'Dropdown options',
                        'prompt'       => 'Add',
                        'option_label' => 'Value',
                        'option_key'   => 'Key',
                    ],
                    'default' => [
                        'label' => 'Value by default',
                    ],
                ],
                'email'     => [
                    'send_notify'   => [
                        'label' => 'Send notify to entered Email address',
                    ],
                    'mail_template' => [
                        'label' => 'Mail template',
                    ],
                ],
                'string'    => [
                    'default' => [
                        'label' => 'Field value by default'
                    ],
                ],
                'recaptcha' => [
                    'theme' => [
                        'label' => 'Theme',
                        'light' => 'Light',
                        'dark'  => 'Dark',
                    ],
                    'size'  => [
                        'label'   => 'Size',
                        'normal'  => 'Normal',
                        'compact' => 'Compact',
                    ],
                ],
                'upload'    => [
                    'mimes'    => [
                        'label'   => 'Allowed file types divided by comma',
                        'comment' => 'Leave empty for any files',
                    ],
                    'max'      => [
                        'label'   => 'Maximum file size',
                        'comment' => 'Maximum file size (KB)',
                    ],
                    'multiple' => [
                        'label' => 'Allow uploading multiple files',
                    ],
                ],
            ],
        ],
        'form'       => [
            'columns'    => [
                'name'      => [
                    'label' => 'Form',
                ],
                'is_active' => [
                    'label' => 'Active',
                ],
            ],
            'fields'     => [
                'tab_fields'        => 'Fields',
                'tab_description'   => 'Description',
                'tab_success'       => 'Success',
                'tab_extra'         => 'Extra',
                'tab_notifications' => 'Notifications',

                'is_active'      => [
                    'label'   => 'Form is active',
                    'comment' => 'Disabled form cant accept submissions',
                ],
                'name'           => [
                    'label'   => 'Form title',
                    'comment' => 'Enter form title',
                ],
                'key'            => [
                    'label'   => 'Form key',
                    'comment' => 'Key for rendering form on site',
                ],
                '_fields'        => [
                    'label'   => 'Fields',
                    'comment' => 'Form fields settings',
                ],
                '_description'   => [
                    'label'   => 'Form description',
                    'comment' => 'Description for render under form title',
                ],
                '_success'       => [
                    'label'   => 'Success',
                    'comment' => 'Text user will see after successful form submission',
                ],
                'success_title'  => [
                    'label' => 'Success message title',
                ],
                'success_msg'    => [
                    'label' => 'Success message text',
                ],
                '_config'        => [
                    'label'   => 'Extra',
                    'comment' => 'Additional form settings',
                ],
                'wrapper_class'  => [
                    'label'   => 'Form wrapper',
                    'comment' => 'CSS class for wrapping form',
                ],
                'form_class'     => [
                    'label'   => 'Form class',
                    'comment' => 'Form CSS class',
                ],
                'submit_label'   => [
                    'label'   => 'Submit button',
                    'comment' => 'Submit button label',
                ],
                'submit_class'   => [
                    'label'   => 'Submit button class',
                    'comment' => 'Submit button CSS class',
                ],
                'clear_label'    => [
                    'label'   => 'Clear button',
                    'comment' => 'Clear button label',
                ],
                'clear_class'    => [
                    'label'   => 'Clear button class',
                    'comment' => 'Clear button CSS class',
                ],
                'clear_visible'  => [
                    'label'   => 'Clear button visible',
                    'comment' => 'Turn off for hide clear button',
                ],
                '_notifications' => [
                    'label'   => 'Notifications',
                    'comment' => 'Notifications settings',
                ],
                'mail_template'  => [
                    'label'              => 'Mail template',
                    'comment'            =>
                        'Mail template for notify about submission. By default - template, :link',
                    'comment_link_label' => 'selected in settings',
                    'emptyOption'        => '- Default -',
                ],
                '_extra_emails'  => [
                    'label'   => 'Email address',
                    'comment' => 'Additional Email addresses for notifying about submissions',
                ],
                'extra_emails'   => [
                    'prompt'      => 'Add',
                    'email_label' => 'Email address'
                ],
            ],
            'validation' => [
                'name'         => [
                    'required' => 'Enter form name',
                ],
                'key'          => [
                    'required'   => 'Enter form key',
                    'unique'     => 'Form with this key already exists',
                    'alpha_dash' => 'Key must contain chars numbers and underscores',
                ],
                'extra_emails' => [
                    'email' => [
                        'required' => 'Enter email address',
                        'email'    => 'Enter correct email address'
                    ],
                ],
            ],
        ],
        'settings'   => [
            'fields'     => [
                'tab_notify'    => 'Notifications',
                'tab_recaptcha' => 'reCAPTCHA',

                '_notify'           => [
                    'label'   => 'Notifications',
                    'comment' => 'Notifications settings',
                ],
                'mail_template'     => [
                    'label'   => 'Mail template for notifications',
                    'comment' => 'Select mail template for use by default',
                ],
                'emails'            => [
                    'label'        => 'Email address',
                    'commentAbove' => 'Enter Email addresses for sending notifications about forms submissions',
                    'prompt'       => 'Add',
                    'email_label'  => 'Email address',
                ],
                'use_queue'         => [
                    'label'   => 'Use queue for sending notifications',
                    'comment' => 'Use only if you know what you are doing!',
                ],
                '_recaptcha'        => [
                    'label'   => 'reCAPTCHA',
                    'comment' => 'reCAPTCHA settings',
                ],
                'recaptcha_sitekey' => [
                    'label'     => 'Site key',
                    'comment_1' => 'Site key can be found in',
                    'comment_2' => 'reCAPTCHA control panel',
                ],
                'recaptcha_secret'  => [
                    'label'     => 'Secret key',
                    'comment_1' => 'Secret key can be found in',
                    'comment_2' => 'reCAPTCHA control panel',
                ],
            ],
            'validation' => [
                'email' => [
                    'required' => 'Enter email address',
                    'email'    => 'Enter correct email address',
                ]
            ],
        ],
        'submission' => [
            'columns'    => [
                'form_id'    => [
                    'label' => 'Form',
                ],
                'created_at' => [
                    'label' => 'Sent at',
                ],
                'viewed_at'  => [
                    'label' => 'Viewed at',
                ],
            ],
            'validation' => [
                'form_id'   => [
                    'required' => 'Choose form',
                    'exists'   => 'Selected form is not exists',
                ],
                'data'      => [
                    'required' => 'Fill form fields',
                    'array'    => 'Fill form fields',
                ],
                'viewed_at' => [
                    'date' => 'Incorrect date',
                ],
            ],
        ],
    ],
    'validation'  => [
        'captcha' => [
            'confirm' => 'Confirm you are not a robot',
        ],
    ],
    'messages'    => [
        'access_denied'  => 'Access denied',
        'deleted'        => 'Deleted',
        'yes'            => 'Yes',
        'no'             => 'No',
        'ok'             => 'OK',
        'cancel'         => 'Cancel',
        'close'          => 'Close',
        'form_not_found' => 'Form not found',
        'no_submissions' => 'There is no submissions for this form',
        'no_forms'       => 'There is no forms on the site',
    ],
    'plugin'      => [
        'name'        => 'Forms',
        'description' => 'Frontend forms constructor',
        'menu'        => [
            'forms'    => [
                'label' => 'Forms',
            ],
            'settings' => [
                'label'       => 'Forms plugin settings',
                'description' => 'Notifications and reCAPTCHA settings',
                'category'    => 'Forms',
            ]
        ]
    ]
];
