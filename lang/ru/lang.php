<?php

use GromIT\Forms\Models\Field;

return [
    'actions'     => [
        'forms'       => [
            'import' => [
                'validate_file_content' => 'Загруженный файл не содержит конфигурацию формы',
            ],
        ],
        'submissions' => [
            'create' => [
                'form_is_disabled'  => 'Форма отключена, заполнения не принимаются',
                'recaptcha_confirm' => '',
                'field_not_found'   => 'Поле :key не найдено'
            ]
        ]
    ],
    'permissions' => [
        'tab'                => 'Формы',
        'edit_forms'         => 'Редактирование форм',
        'edit_settings'      => 'Изменение настроек уведомлений',
        'show_submissions'   => 'Просмотр заполнений форм',
        'export_submissions' => 'Экспорт заполнений форм',
        'delete_submissions' => 'Удаление заполнений форм',
    ],
    'components'  => [
        'forms' => [
            'details'            => [
                'name'        => 'Форма',
                'description' => 'Выводит форму на страницу',
            ],
            'properties'         => [
                'form_key'   => [
                    'title'       => 'Форма',
                    'description' => 'Выберите форму для вывода',
                ],
                'add_styles' => [
                    'title'       => 'Подключить стили',
                    'description' => 'Подключить стандартный набор стилей',
                ],
            ],
            'form_is_not_active' => 'форма не активна',
            'unknown_form'       => 'Неизвестная форма',
        ]
    ],
    'controllers' => [
        'fields'      => [
            'recaptcha_comment_1' => 'Для работы поля требуется заполнить ключи в',
            'recaptcha_comment_2' => 'настройках форм',
            'back_to_form'        => 'Вернуться к форме',
            'reorder'             => [
                'title' => 'Сортировка полей',
            ]
        ],
        'forms'       => [
            'add_fields_after_create_msg' => 'Добавить поля можно будет после создания формы',
            'breadcrumbs_root'            => 'Формы',
            'return_to_forms_list'        => 'Вернуться к списку форм',
            'record_name'                 => 'Форма',

            'create_page_title' => 'Новая форма',
            'update_page_title' => 'Изменение формы',
            'list_page_title'   => 'Формы',

            'form_not_found' => 'Форма не найдена',

            'relation_fields_label'        => 'Поле',
            'relation_fields_manage_title' => 'Настройка поля',

            'upload_form_title'         => 'Загрузка формы',
            'upload_form_btn_text'      => 'Загрузить',
            'upload_must_be_json'       => 'Файл должен быть в формате json',
            'upload_file_field_label'   => 'Файл формы',
            'upload_file_field_comment' => 'Выберите файл с конфигурацией формы',

            'copy_name_field_label'   => 'Название формы',
            'copy_name_field_comment' => 'Название формы для отображения в списке форм',
            'copy_key_field_label'    => 'Ключ формы',
            'copy_key_field_comment'  => 'Ключ для вывода формы на сайте',
            'copy_form_title'         => 'Копирование формы',
            'copy_form_btn_text'      => 'Скопировать',

            'btn_add_field'                     => 'Добавить поле',
            'btn_delete_field'                  => 'Удалить поле',
            'btn_delete_field_confirm'          => 'Вы уверены?',
            'btn_reorder_fields'                => 'Сортировать',
            'btn_add_form'                      => 'Добавить форму',
            'btn_delete_selected_forms'         => 'Удалить выбранные',
            'btn_delete_selected_forms_confirm' =>
                'Удалить выбранные формы? Также будут удалены все полученные заполнения выбранных форм!',
            'btn_upload_form'                   => 'Загрузить форму',
            'btn_submissions'                   => 'Отправки',
            'btn_create'                        => 'Создать',
            'btn_create_and_close'              => 'Создать и закрыть',
            'btn_create_load'                   => 'Создание',
            'btn_save'                          => 'Сохранить',
            'btn_save_and_close'                => 'Сохранить и закрыть',
            'btn_save_load'                     => 'Сохранение',
            'btn_copy_form'                     => 'Скопировать форму',
            'btn_export_form'                   => 'Экспортировать форму',
            'btn_delete_confirm'                =>
                'Удалить форму? Также будут удалены все полученные заполнения формы!',
            'btn_delete_load'                   => 'Удаление',
        ],
        'submissions' => [
            'breadcrumbs_root'           => 'Заполнения форм',
            'files_not_uploaded'         => 'Файлы не загружены',
            'return_tu_submissions_list' => 'Вернуться к заполнениям',
            'filter_form'                => 'Форма',
            'record_name'                => 'Заполнение',
            'preview_page_title'         => 'Просмотр заполнения',
            'list_page_title'            => 'Заполнения форм',

            'export' => [
                'form_id'        => [
                    'label'   => 'Форма',
                    'comment' => 'Выберите форму для экспорта отправлений',
                ],
                'popup_title'    => 'Экспорт отправлений',
                'popup_btn_text' => 'Экспортировать',
                'validation'     => [
                    'form_id.required' => 'Форма не указана',
                    'form_id.exists'   => 'Указанная форма не существует',
                ]
            ],

            'btn_export'                  => 'Экспортировать',
            'btn_back'                    => 'Назад',
            'btn_back_to_forms'           => 'Вернуться к списку форм',
            'btn_delete_selected'         => 'Удалить выбранные',
            'btn_delete_selected_confirm' => 'Удалить выбранные заполнения?',
            'btn_delete_confirm'          => 'Удалить?',
            'btn_delete_load'             => 'Удаление',
        ],
    ],
    'models'      => [
        'field'      => [
            'columns'            => [
                'label'       => 'Поле',
                'type'        => 'Тип',
                'is_required' => 'Обязательно',
                'sort_order'  => 'Порядок вывода',
            ],
            'fields'             => [
                'label'            => [
                    'label'   => 'Название поля',
                    'comment' => 'Метка поля на форме',
                ],
                'key'              => [
                    'label'   => 'Ключ поля',
                    'comment' => 'Ключ поля для вывода на странице и получения значений формы',
                ],
                'comment'          => [
                    'label'   => 'Комментарий',
                    'comment' => 'Подсказка к полю',
                ],
                'is_required'      => [
                    'label' => 'Поле обязательно для заполнения',
                ],
                'required_message' => [
                    'label'   => 'Сообщение о необходимости заполнить поле',
                    'comment' => 'Сообщение, выводимое пользователю, при отправке формы с не заполненным полем',
                ],
                'wrapper_class'    => [
                    'label'   => 'Класс обертки',
                    'comment' => 'CSS-класс обертки поля',
                ],
                'field_class'      => [
                    'label'   => 'Класс поля',
                    'comment' => 'CSS-класс поля',
                ],
            ],
            'validation'         => [
                'form_id' => [
                    'required' => 'Укажите форму',
                    'exists'   => 'Указанная форма не существует',
                ],
                'label'   => [
                    'required' => 'Введите название поля',
                ],
                'key'     => [
                    'required' => 'Введите ключ поля',
                    'exists'   => 'Поле с таким ключом на этой форме уже есть',
                ],
                'type'    => [
                    'required' => 'Выберите тип поля',
                ],
            ],
            'type'               => [
                Field::TYPE_STRING        => 'Строковое поле',
                Field::TYPE_EMAIL         => 'Email-адрес',
                Field::TYPE_NUMBER        => 'Числовое поле',
                Field::TYPE_TEXTAREA      => 'Текстовое поле',
                Field::TYPE_CHECKBOX      => 'Галочка',
                Field::TYPE_SELECT        => 'Выпадающий список',
                Field::TYPE_RADIO         => 'Выбор из нескольких вариантов',
                Field::TYPE_CHECKBOX_LIST => 'Выбор нескольких пунктов из списка',
                Field::TYPE_UPLOAD        => 'Загрузка файла',
                Field::TYPE_RECAPTCHA     => 'reCAPTCHA',
            ],
            'defaults'           => [
                'checkbox' => 'Отмечено по умолчанию',
                'others'   => 'Значение по умолчанию',
            ],
            'dynamic_validation' => [
                'required_string'   => 'Поле :label обязательно для заполнения',
                'required_checkbox' => 'Необходимо отметить :label',
                'required_select'   => 'Выберите что-нибудь из :label',
                'required_upload'   => 'Выберите файл',

                'email' => 'Введите корректный адрес эл.почты в поле :label',
                'in'    => 'Выберите корректное значение в :label',

                'upload_mimes'    => 'Тип файла не поддерживается (Поддерживаемые типы файлов: :mimes)',
                'upload_max_size' => 'Максимальный размер файла :maxSize КБ',
            ],
            'only_for_upload'    => 'Только для файлов',
            'only_for_email'     => 'Только для email-адресов',
            'fields_by_type'     => [
                'select'    => [
                    'options' => [
                        'label'        => 'Пункты списка',
                        'prompt'       => 'Добавить',
                        'option_label' => 'Значение',
                        'option_key'   => 'Ключ значения',
                    ],
                    'default' => [
                        'label' => 'Значение поля по умолчанию',
                    ],
                ],
                'email'     => [
                    'send_notify'   => [
                        'label' => 'Отправлять уведомление на Email-адрес, введенный в поле',
                    ],
                    'mail_template' => [
                        'label' => 'Шаблон письма',
                    ],
                ],
                'string'    => [
                    'default' => [
                        'label' => 'Значение поля по умолчанию'
                    ],
                ],
                'recaptcha' => [
                    'theme' => [
                        'label' => 'Тема',
                        'light' => 'Светлая',
                        'dark'  => 'Темная',
                    ],
                    'size'  => [
                        'label'   => 'Размер',
                        'normal'  => 'Обычный',
                        'compact' => 'Компактный',
                    ],
                ],
                'upload'    => [
                    'mimes'    => [
                        'label'   => 'Разрешенные форматы файлов через запятую',
                        'comment' => 'Оставьте пустым, если разрешено загружать любые файлы',
                    ],
                    'max'      => [
                        'label'   => 'Максимальный размер файла',
                        'comment' => 'Максимальный размер файла в килобайтах',
                    ],
                    'multiple' => [
                        'label' => 'Разрешить загрузку нескольких файлов',
                    ],
                ],
            ],
        ],
        'form'       => [
            'columns'    => [
                'name'      => [
                    'label' => 'Форма',
                ],
                'is_active' => [
                    'label' => 'Активно',
                ],
            ],
            'fields'     => [
                'tab_fields'        => 'Поля формы',
                'tab_description'   => 'Описание формы',
                'tab_success'       => 'Успешная отправка',
                'tab_extra'         => 'Дополнительно',
                'tab_notifications' => 'Уведомления',

                'is_active'      => [
                    'label'   => 'Форма активна',
                    'comment' => 'Если отключить, заполнения формы приниматься не будут',
                ],
                'name'           => [
                    'label'   => 'Заголовок формы',
                    'comment' => 'Введите заголовок формы',
                ],
                'key'            => [
                    'label'   => 'Ключ формы',
                    'comment' => 'Ключ для вывода формы на сайте',
                ],
                '_fields'        => [
                    'label'   => 'Поля',
                    'comment' => 'Настройка полей формы',
                ],
                '_description'   => [
                    'label'   => 'Описание формы',
                    'comment' => 'Описание формы для вывода под заголовком',
                ],
                '_success'       => [
                    'label'   => 'Сообщение об успехе',
                    'comment' => 'Сообщение, отображаемое на странице после успешной отправки формы',
                ],
                'success_title'  => [
                    'label' => 'Заголовок сообщения об успехе',
                ],
                'success_msg'    => [
                    'label' => 'Сообщение об успешном заполнении формы',
                ],
                '_config'        => [
                    'label'   => 'Дополнительные настройки',
                    'comment' => 'Дополнительные настройки формы',
                ],
                'wrapper_class'  => [
                    'label'   => 'Класс обертки',
                    'comment' => 'CSS-класс обертки формы',
                ],
                'form_class'     => [
                    'label'   => 'Класс формы',
                    'comment' => 'CSS-класс формы',
                ],
                'submit_label'   => [
                    'label'   => 'Кнопка отправки',
                    'comment' => 'Подпись кнопки отправки формы',
                ],
                'submit_class'   => [
                    'label'   => 'Класс кнопки отправки',
                    'comment' => 'CSS-класс кнопки отправки',
                ],
                'clear_label'    => [
                    'label'   => 'Кнопки очистки',
                    'comment' => 'Подпись кнопки очистки формы',
                ],
                'clear_class'    => [
                    'label'   => 'Класс кнопки очистки',
                    'comment' => 'CSS-класс кнопки очистки',
                ],
                'clear_visible'  => [
                    'label'   => 'Отображать кнопку очистки формы',
                    'comment' => 'Выключите, чтобы спрятать кнопку',
                ],
                '_notifications' => [
                    'label'   => 'Уведомления',
                    'comment' => 'Настройка уведомлений',
                ],
                'mail_template'  => [
                    'label'              => 'Шаблон письма-уведомления',
                    'comment'            =>
                        'Шаблон письма с уведомлением о заполнении формы. По умолчанию - шаблон, :link',
                    'comment_link_label' => 'указанный в настройках',
                    'emptyOption'        => '- По умолчанию -',
                ],
                '_extra_emails'  => [
                    'label'   => 'Email-адреса',
                    'comment' => 'Дополнительные Email-адреса для отправки уведомлений о заполнении формы',
                ],
                'extra_emails'   => [
                    'prompt'      => 'Добавить email-адрес',
                    'email_label' => 'Email-адрес'
                ],
            ],
            'validation' => [
                'name'         => [
                    'required' => 'Введите название формы',
                ],
                'key'          => [
                    'required'   => 'Введите ключ формы',
                    'unique'     => 'Форма с таким ключом уже есть на сайте',
                    'alpha_dash' => 'Ключ должен состоять из латинских букв, цифр и знаков подчеркивания',
                ],
                'extra_emails' => [
                    'email' => [
                        'required' => 'Введите email-адрес',
                        'email'    => 'Введите корректный email-адрес'
                    ],
                ],
            ],
        ],
        'settings'   => [
            'fields'     => [
                'tab_notify'    => 'Уведомления',
                'tab_recaptcha' => 'reCAPTCHA',

                '_notify'           => [
                    'label'   => 'Уведомления',
                    'comment' => 'Настройка уведомлений о заполнениях форм',
                ],
                'mail_template'     => [
                    'label'   => 'Шаблон писем для уведомлений',
                    'comment' => 'Выберите шаблон письма для уведомлений, используемый по умолчанию',
                ],
                'emails'            => [
                    'label'        => 'Email-адреса',
                    'commentAbove' => 'Введите Email-адреса получателей уведомлений о заполнении форм на сайте',
                    'prompt'       => 'Добавить email-адрес',
                    'email_label'  => 'Email-адрес',
                ],
                'use_queue'         => [
                    'label'   => 'Отправлять уведомления через очередь',
                    'comment' => 'Отмечайте, только если точно уверены в том, что делаете!',
                ],
                '_recaptcha'        => [
                    'label'   => 'reCAPTCHA',
                    'comment' => 'Настройки подключения reCAPTCHA',
                ],
                'recaptcha_sitekey' => [
                    'label'     => 'Ключ сайта',
                    'comment_1' => 'Ключ сайта можно скопировать из',
                    'comment_2' => 'панели управления reCAPTCHA',
                ],
                'recaptcha_secret'  => [
                    'label'     => 'Секретный ключ',
                    'comment_1' => 'Секретный ключ можно скопировать из',
                    'comment_2' => 'панели управления reCAPTCHA',
                ],
            ],
            'validation' => [
                'email' => [
                    'required' => 'Введите адрес эл.почты',
                    'email'    => 'Введите коррестный адрес эл.почты',
                ]
            ],
        ],
        'submission' => [
            'columns'    => [
                'form_id'    => [
                    'label' => 'Форма',
                ],
                'created_at' => [
                    'label' => 'Отправлено',
                ],
                'viewed_at'  => [
                    'label' => 'Просмотрено',
                ],
            ],
            'validation' => [
                'form_id'   => [
                    'required' => 'Форма не указана',
                    'exists'   => 'Указанная форма не существует',
                ],
                'data'      => [
                    'required' => 'Форма не заполнена',
                    'array'    => 'Форма не заполнена',
                ],
                'viewed_at' => [
                    'date' => 'Некорректная дата',
                ],
            ],
        ],
    ],
    'validation'  => [
        'captcha' => [
            'confirm' => 'Подтвердите, что вы не робот',
        ],
    ],
    'messages'    => [
        'access_denied'  => 'Ошибка доступа',
        'deleted'        => 'Удалено',
        'yes'            => 'Да',
        'no'             => 'Нет',
        'ok'             => 'Ок',
        'cancel'         => 'Отменить',
        'close'          => 'Закрыть',
        'form_not_found' => 'Форма не найдена',
        'no_submissions' => 'Форму ни разу не заполнили',
        'no_forms'       => 'На сайт не добавлено ни одной формы',
    ],
    'plugin'      => [
        'name'        => 'Формы',
        'description' => 'Конструктор форм для сайта',
        'menu'        => [
            'forms'    => [
                'label' => 'Формы',
            ],
            'settings' => [
                'label'       => 'Настройки плагина форм',
                'description' => 'Настройки уведомлений и reCAPTCHA',
                'category'    => 'Формы',
            ]
        ]
    ]
];
