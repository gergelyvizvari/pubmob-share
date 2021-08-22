<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helper', function (Blueprint $table) {
            $table->id();
            $table->string('id_str', 255);
            $table->string('title', 1024);
            $table->string('type', 255);
            $table->text('container_title');
            $table->string('ISSN', 60);
            $table->integer('issue');
            $table->string('language', 60);
            $table->text('note');
            $table->string('page', 60);
            $table->integer('volume');
            $table->integer('issued')->nullable();
            $table->timestamp('issued_date')->nullable();
            $table->text('authors');
            $table->string('doi', 255);
            $table->text('abstract');
            $table->string('url', 255);
            $table->string('source', 255);
            $table->string('journal_abbreviation', 255);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('helper');
    }
}
