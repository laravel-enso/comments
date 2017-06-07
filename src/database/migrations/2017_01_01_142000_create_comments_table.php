<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('restrict')->onDelete('restrict');
            $table->morphs('commentable');
            $table->string('body');
            $table->integer('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('restrict')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
