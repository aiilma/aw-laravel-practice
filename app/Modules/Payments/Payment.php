<?php

namespace Artworch\Modules\Payments;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount',
        'created_at', 'updated_at',
        'type', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
