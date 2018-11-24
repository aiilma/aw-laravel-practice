<?php

namespace Artworch\Modules\User\Account;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_customer', 'order_token',
        'status', 'user_data',
        'created_at', 'updated_at',
        'downloaded_at',
    ];

    // * id_customer (int, foreign)
    // * order_token (string255, primary)
    // * status (null default, char[1-4])
    // * user_data (json):
    //     - project_token (string255, foreign)
    //     - user_visualization;
    //     - user_background;
    //     - user_inputs;
    // * created_at ()
    // * updated_at ()
    // * downloaded_at (null default, datetime)

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    // TODO: метод получения данных о заказе пользователя (поле user_data в представлении массива)

}
