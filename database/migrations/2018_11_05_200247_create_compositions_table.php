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

                $table->char('view_status', 1)->default('0')->comment('Статус активности композиции в листинге (0 - hide; 1 - show)');

                $table->timestamp('published_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->datetime('expire_at')->comment('Дата блокировки продажи композиции');
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
