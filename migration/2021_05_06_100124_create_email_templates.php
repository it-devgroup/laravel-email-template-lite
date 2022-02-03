<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            '{{TABLE_NAME}}',
            function (Blueprint $table) {
                $table->id();
                $table->string('type')->index();
                $table->string('title');
                $table->string('subject');
                $table->text('body');
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{{TABLE_NAME}}');
    }
}
