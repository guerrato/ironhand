<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nickname')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('image')->nullable();
            $table->string('image_name')->nullable();
            $table->enum('gender', ['female','male']);
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('facebook')->nullable();
            $table->string('uuid')->unique()->nullable();
            $table->integer('status_id')->unsigned()->default(1);
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('status_id')->references('id')->on('member_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
