<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberHasRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_has_roles', function (Blueprint $table) {
            $table->integer('member_id')->unsigned();
            $table->integer('ministry_id')->unsigned();
            $table->integer('role_id')->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('ministry_id')->references('id')->on('ministries');
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('role_id')->references('id')->on('member_roles');

            $table->primary(['member_id', 'ministry_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_has_roles');
    }
}
