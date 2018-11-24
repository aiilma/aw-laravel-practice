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

                $table->string('title', 64);
                $table->decimal('custom_price', 8, 2)->unsigned();
                $table->char('visualization', 1)->comment('Допустимые формы отображения/визуализации (L / S), характерные проекту. Определяет пользователь');
                $table->json('inputs')->nullable()->comment('Поля формы, которые определяются пользователем');
                $table->string('project_token', 255)->unique();
                $table->char('accept_status', 1)->nullable()->comment('Статус рассмотрения заявки входящей композиции пользователя. Определяется администрацией. Не рассмотрена - (null), рассмотрена - (1), отклонена - (0)');
                
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
        Schema::dropIfExists('comp_requests');
    }
}
