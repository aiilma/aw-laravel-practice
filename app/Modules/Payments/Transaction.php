<?php

namespace Artworch\Modules\Payments;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * @var string
     */
    protected $table = 'transactions';

    /**
     * @var array
     */
    protected $dates = ['created_at, updated_at, deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['transaction_id', 'user_id', 'amount', 'type', 'status'];
}