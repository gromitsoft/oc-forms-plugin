<?php

/** @noinspection AutoloadingIssuesInspection */

namespace GromIT\Forms\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('gromit_forms_submissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->unsignedInteger('form_id');
            $table->foreign('form_id')
                ->references('id')
                ->on('gromit_forms_forms')
                ->onDelete('cascade');

            $table->text('data');
            $table->text('request_data')->nullable();
            $table->text('user_data')->nullable();

            $table->dateTime('viewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gromit_forms_submissions');
    }
}
