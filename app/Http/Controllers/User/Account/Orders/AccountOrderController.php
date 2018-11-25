<?php

namespace Artworch\Http\Controllers\User\Account\Orders;

use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;

class AccountOrderController extends Controller
{
    /**
     * Показывает список заказов по id пользователя
     *
     * @return array
     */
    public function showList(Request $request)
    {
        return view('systems.user.account.orders.list', ['orders' => $request->session()->get('orders_cart')]);
    }

    /**
     * Вовзращает архив в качестве финального продукта по токену заказа
     * download/order_token/pathtoarchive
     *
     * @return file
     */
    public function downloadProduct(Request $request, $orderHash)
    {
        // return response()->url('~download/order_token/pathtoarchive...');
    }
}
