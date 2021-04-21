<?php

/** @noinspection AutoloadingIssuesInspection */

namespace GromIT\Forms\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;

class CreateFieldsTable extends Migration
{
    public function up(): void
    {
        Schema::create('gromit_forms_fields', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->unsignedInteger('form_id');
            $table->foreign('form_id')
                ->references('id')
                ->on('gromit_forms_forms')
                ->onDelete('cascade');

            $table->string('key');
            $table->string('label');
            $table->string('comment')->nullable();
            $table->string('type');
            $table->string('default')->nullable();
            $table->boolean('is_required')->default(false);
            $table->string('required_message')->nullable();
            $table->string('wrapper_class')->nullable();
            $table->string('field_class')->nullable();
            $table->text('options')->nullable();

            $table->integer('sort_order')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gromit_forms_fields');
    }
}
