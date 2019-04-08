<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->comment('uuid');
            $table->string('username')->comment('用户名')->unique();
            $table->string('password')->comment('密码');
            $table->string('email')->comment('邮箱')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            //notice需要其他字段在此添加即可
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
