<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->increments('transaction_id');
                $table->unsignedInteger('user_id')->comment('Внешний ИД автора композиции');
                $table->string('transaction_code')->nullable();
                $table->decimal('amount', 8, 2)->unsigned();
                $table->text('message')->nullable();
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();
                $table->string('method')->comment('Способ оплаты');
                $table->char('type', 1); // i / o ?
                $table->char('confirm_status', 1)->nullable(); // 0 / 1 / null for output 
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
        Schema::dropIfExists('payment_transactions');
    }
}
