<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compositions', function (Blueprint $table) {
            $table->unsignedInteger('comp_request_id')->after('id')->comment('Внешний ИД данных композиции из пользовательской заявки на загрузку');
            $table->foreign('comp_request_id')->references('id')->on('comp_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compositions', function (Blueprint $table) {
            $table->dropForeign('compositions_comp_request_id_foreign');
        });
    }
}
