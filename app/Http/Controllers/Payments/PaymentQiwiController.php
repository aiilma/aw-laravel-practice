<?php

namespace Artworch\Http\Controllers\Payments;

use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;
use Artworch\Lib\QiwiApi;

class PaymentQiwiController extends Controller
{
    /*
    * Process payment with express checkout
    */
    public function paywithQiwi(Request $request)
    {
        // получить данные с формы ($request): номер телефона, сумма для перевода
        // валидировать данные

        // если есть ошибки, то
        //     вернуть пользователю в качестве ответа сообщение об ошибке

        // перевести
        // если транзакция успешно совершена, то
        //     редирект
        // иначе
        //     вернуть пользователю в качестве ответа сообщение об ошибке
        



        $phone  = '+79652420913';
        $token = config('qiwi_payment.account.api_token');
        $api = new QiwiApi($phone, $token);

        dd($api->sendMoneyToQiwi([
            'id' => '11111111111111',
            'sum' => [
                'amount'   => 100,
                'currency' => '643',
            ],
            'paymentMethod' => [
                'type' => 'Account',
                'accountId' => '643'
            ],
            'comment' => 'test',
            'fields' => [
                'account' => '+79121112233'
            ]
        ]));
    }
}
