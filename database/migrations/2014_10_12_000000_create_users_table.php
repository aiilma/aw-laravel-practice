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
                $table->string('username')->comment('Никнейм пользователя');
                $table->string('email')->unique()->comment('E-mail пользователя');
                $table->timestamp('email_verified_at')->nullable()->comment('Дата подтверждения e-mail');
                $table->string('password')->comment('Пароль пользователя');
                $table->string('token')->nullable()->comment('Токен для верификации нового аккаунта');
                $table->rememberToken()->comment('Хэш-код пользователя');
                $table->timestamps();
                $table->string('steamid', 128)->unique()->nullable()->comment('Первичный уникальный ИД пользователя steam');
                $table->string('avatar')->nullable()->comment('Аватарка пользователя');
                $table->decimal('balance', 8, 2)->default('0.00')->unsigned()->comment('Лицевой счет/баланс пользователя');
                $table->char('status', 2)->default('1')->comment('Статус пользователя на сайте');
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
