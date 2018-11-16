<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCompRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comp_requests', function (Blueprint $table) {
            $table->unsignedInteger('author_id')->comment('Внешний ИД автора композиции');
            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comp_requests', function (Blueprint $table) {
            $table->dropForeign('comp_requests_author_id_foreign');
        });
    }
}
