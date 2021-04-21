<?php

/** @noinspection AutoloadingIssuesInspection */

namespace GromIT\Forms\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    public function up(): void
    {
        Schema::create('gromit_forms_forms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('name');
            $table->string('key')->unique();

            $table->text('description')->nullable();
            $table->string('success_title')->nullable();
            $table->text('success_msg')->nullable();
            $table->string('wrapper_class')->nullable();
            $table->string('form_class')->nullable();
            $table->text('extra_emails')->nullable();
            $table->string('mail_template')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gromit_forms_forms');
    }
}
