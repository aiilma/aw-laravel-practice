<?php

namespace Artworch\Modules\Payments;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    /**
     * @var string
     */
    protected $table = 'payment_transactions';

    /**
     * @var array
     */
    protected $dates = ['created_at, updated_at, deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'transaction_id', 'transaction_code', 'amount', 'method', 'type', 'confirm_status'];
}