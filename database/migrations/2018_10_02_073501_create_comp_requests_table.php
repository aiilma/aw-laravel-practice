<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('comp_requests')) {
            Schema::create('comp_requests', function (Blueprint $table) {
                $table->increments('id')->comment('Первичный уникальный ИД заявки на загрузку композиции');

                $table->string('archive_path', 255)->unique()->comment('Путь хранения проекта на сервере');
                $table->string('title', 192)->comment('Название композиции. Определяет пользователь');
                $table->decimal('custom_price', 8, 2)->comment('Цена композиции. Определяет пользователь');
                $table->string('visualization', 32)->comment('Допустимые формы отображения/визуализации, характерные проекту. Определяет пользователь');
                $table->json('inputs')->nullable()->comment('Поля формы, которые определяются пользователем');
                $table->timestamps();
                $table->char('status', 2)->default('0')->comment('Статус рассмотрения заявки входящей композиции пользователя. Определяется администрацией. Не рассмотрена - (0), рассмотрена - (1), отклонена - (-1)');
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
        Schema::dropIfExists('comp_requests');
    }
}
