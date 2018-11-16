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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id')->comment('Первичный уникальный ИД пользователя');

                $table->string('username')->comment('Никнейм');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('token')->nullable()->comment('Токен для верификации нового аккаунта');
                $table->string('steamid', 128)->unique()->nullable();
                $table->string('avatar')->nullable();
                $table->decimal('balance', 8, 2)->default('0.00')->unsigned()->comment('Лицевой счет/баланс');
                $table->rememberToken()->comment('"Remember me" токен');
                $table->char('status', 2)->default('1')->comment('Статус пользователя на сайте');
                
                $table->timestamp('email_verified_at')->nullable()->comment('Дата подтверждения e-mail');
                $table->timestamps();
            });
        }
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
