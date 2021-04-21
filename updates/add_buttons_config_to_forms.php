<?php

namespace GromIT\Forms\Updates;

use GromIT\Forms\Models\Form;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;

class AddButtonsConfigToForms extends Migration
{
    public function up()
    {
        Schema::table('gromit_forms_forms', function (Blueprint $table) {
            $table->text('buttons_config')->nullable()->after('form_class');
        });

        Form::all()->each(function (Form $form) {
            $form->buttons_config = [
                'submit_label'  => 'Submit',
                'clear_label'   => 'Clear',
                'clear_visible' => '1',
            ];

            $form->forceSave();
        });

        Schema::table('gromit_forms_forms', function (Blueprint $table) {
            $table->text('buttons_config')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('gromit_forms_forms', function (Blueprint $table) {
            $table->dropColumn('buttons_config');
        });
    }
}
