<?php

namespace Artworch\Http\Controllers\User\Account\Orders;

use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;
use Artworch\Modules\User\Account\Order;
use Artworch\Modules\User\Account\CompRequest;

class AccountOrderController extends Controller
{
    /**
     * Показывает список заказов по id пользователя
     *
     * @return array
     */
    public function showList(Request $request)
    {
        $response = [
            'unconfirmedOrders' => null,
            'confirmedOrders' => null,
        ];

        // если есть неподтвержденные заказы ...
        if ($request->session()->has('orders_cart'))
        {
            foreach ($request->session()->get('orders_cart') as $index => $orderInfo)
            {
                $response['unconfirmedOrders'][$index]['composition']['id'] = CompRequest::where('project_token', '=', $orderInfo['compHash'])->first()->composition->id;
                $response['unconfirmedOrders'][$index]['composition']['title'] = CompRequest::where('project_token', '=', $orderInfo['compHash'])->first()->title;
                $response['unconfirmedOrders'][$index]['composition']['hash'] = $orderInfo['compHash'];

                $response['unconfirmedOrders'][$index]['order']['formData']['visualization'] = $orderInfo['visualization'];
                $response['unconfirmedOrders'][$index]['order']['formData']['background'] = $orderInfo['background'];
                $response['unconfirmedOrders'][$index]['order']['hash'] = $orderInfo['orderHash'];
            }
        }

        $response['confirmedOrders'] = auth()->user()->orders;
        return view('systems.user.account.orders.list', $response);
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
