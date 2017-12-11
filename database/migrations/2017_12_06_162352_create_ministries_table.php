<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ministries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->mediumText('description')->nullable();
            $table->enum('required_gender', ['female','male'])->nullable();
            $table->integer('coordinator_id')->unsigned();
            $table->integer('default_demographic_id')->unsigned();
            $table->timestamps();

            $table->foreign('coordinator_id')->references('id')->on('users');
            $table->foreign('default_demographic_id')->references('id')->on('demographics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ministries');
    }
}
