<?php

namespace GromIT\Forms\Classes;

class Permissions
{
    public const ANY = 'gromit.forms.*';

    public const EDIT_FORMS = 'gromit.forms.edit_forms';
    public const EXPORT_SUBMISSIONS = 'gromit.forms.export_submissions';
    public const DELETE_SUBMISSIONS = 'gromit.forms.delete_submissions';
    public const SHOW_SUBMISSIONS = 'gromit.forms.show_submissions';
    public const EDIT_SETTINGS = 'gromit.forms.edit_settings';

    /**
     * Lists permissions declared by plugin.
     *
     * @return \string[][]
     */
    public static function lists(): array
    {
        return [
            self::EDIT_FORMS         => [
                'tab'   => 'gromit.forms::lang.permissions.tab',
                'label' => 'gromit.forms::lang.permissions.edit_forms',
            ],
            self::SHOW_SUBMISSIONS   => [
                'tab'   => 'gromit.forms::lang.permissions.tab',
                'label' => 'gromit.forms::lang.permissions.show_submissions',
            ],
            self::EXPORT_SUBMISSIONS => [
                'tab'   => 'gromit.forms::lang.permissions.tab',
                'label' => 'gromit.forms::lang.permissions.export_submissions',
            ],
            self::DELETE_SUBMISSIONS => [
                'tab'   => 'gromit.forms::lang.permissions.tab',
                'label' => 'gromit.forms::lang.permissions.delete_submissions',
            ],
            self::EDIT_SETTINGS      => [
                'tab'   => 'gromit.forms::lang.permissions.tab',
                'label' => 'gromit.forms::lang.permissions.edit_settings',
            ],
        ];
    }
}
