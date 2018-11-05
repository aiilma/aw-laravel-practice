<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('compositions')) {
            Schema::create('compositions', function (Blueprint $table) {
                $table->increments('id')->comment('Первичный уникальный ИД композиции');

                $table->string('title', 32)->comment('Название композиции. Определяет пользователь');
                $table->string('freeze_picture', 128)->unique()->comment('Динамическая превью-картинка композиции. Отображается при наведении');
                $table->string('preview_picture', 128)->unique()->comment('Статическая превью-картинка композиции');
                $table->decimal('custom_price', 8, 2)->unsigned()->comment('Цена композиции. Определяет пользователь');

                $table->timestamp('published_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Дата публикации композиции');
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
        Schema::dropIfExists('compositions');
    }
}
