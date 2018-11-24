<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders')) {
        Schema::create('orders', function (Blueprint $table) {
                $table->string('order_token', 255)->primary();
                $table->char('status', 1)->nullable();
                $table->json('user_data');
                $table->timestamps();
                $table->datetime('downloaded_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
