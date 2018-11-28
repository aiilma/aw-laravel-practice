<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




/**
 * COMPOSITIONS
 */

// compositions list...
Route::get('/compositions', 'Compositions\CompositionController@getListCompositions')->name('compositions');

// composition form...
Route::get('/compositions/c_{composition_id}', 'Compositions\CompositionController@getCompositionInfo')
        ->where(['composition_id' => '[0-9]+'])
        ->name('compositions-fbuy');
// buying from comp listing page...
Route::post('/composition/buy', 'Compositions\CompositionController@buyComposition')->name('compositions-form-buy');


// composition showcase
Route::get('/showcase/c_{composition_id}', 'Compositions\CompositionController@getCompositionInfo')->where(['composition_id' => '[0-9]+']);
// buying from showcase page
Route::post('/showcase/c_{composition_id}', 'Compositions\CompositionController@buyComposition')->where(['composition_id' => '[0-9]+']);




/**
 * ACCOUNT
 */
Route::prefix('/account')->group(function() {

    // .com/account
    Route::get('/', 'User\Account\Account\AccountHomeController@index')->name('acc-home');
    Route::get('/stats', 'User\Account\Account\AccountStatsController@index')->name('acc-stats');
    Route::get('/notifications', 'User\Account\Account\AccountNotificationsController@index')->name('acc-notifications');
    Route::get('/settings', 'User\Account\Account\AccountSettingsController@index')->name('acc-settings');

    // .com/account/prod...
    Route::prefix('/prod')->group(function() {
        Route::get('/', 'User\Account\Production\AccountProductionController@showList')->name('acc-prod-showlist');
        // .com/account/prod/upload
        Route::get('/upload', 'User\Account\Production\AccountProductionController@showUploader')->name('acc-prod-showuploader');
        Route::post('/upload', 'User\Account\Production\AccountProductionController@sendCompRequest')->name('acc-prod-sendcomprequest');
        // .com/account/prod/requests
        Route::get('/requests', 'User\Account\Production\AccountProductionController@showRequests')->name('acc-prod-showrequests');
    });

    // .com/account/orders...
    Route::prefix('/orders')->group(function() {
        Route::get('/', 'User\Account\Orders\AccountOrderController@showList')->name('acc-orders-showlist');
        Route::get('/download/{orderHash}', 'User\Account\Orders\AccountOrderController@downloadProduct')->name('acc-orders-downloadprod');


        /**
         * Confirm order and write order data in database
         * .com/account/orders/confirm
         * @return array
         */
        Route::post('/confirm', function(\Illuminate\Http\Request $request) {

            // получить данные о композиции (прайс)
            // получить данные пользовательского заказа
            $response = [
                'messages' => [
                    'op' => [],
                ],
                'done' => false,
            ];

            // ВАЛИДАЦИЯ ВХОДНЫХ ДАННЫХ
            // если заказ по хешу существует
            if (auth()->user()->orderExistsInSession($request->_orderHash))
            {
                $response['messages']['op'] = auth()->user()->validateUserPermissionsToBuyComposition($request->_compHash);

                // если пользователь не может покупать данную композицию (хватает денежных средств; не больше config(N) заказов в активе), то
                if (count($response['messages']['op']) !== 0)
                {
                    // вывести сообщение с причиной о невозможности выполнения приобретения выбранного продукта
                    return response()->json(['messages' => $response['messages']['op']]);
                }
            }
            else
            {
                // Заказа больше не существует
                $response['messages']['op'][] = 'A time for confirming this order is expired. Please, try to add an order again if you still want to get this product :)';
                return response()->json(['messages' => $response['messages']['op']]);
            }

            // вычисление и обновление баланса обеих сторон (заказчик, дизайнер);
            // добавление информации о заказе в БД;
            // удаление из временного хранилища;
            Artworch\Modules\User\Account\Order::add($request->all());
            $response['done'] = true;
            // отправить данные о заказе на клиентскую сторону (обновить страницу на клиенте)
            return response()->json($response);

        })->name('acc-orders-confirm');

        /**
         * Deny order and delete data of order from session
         * .com/account/orders/deny
         * @return array
         */
        Route::post('/deny', function(\Illuminate\Http\Request $request) {
            $response = [
                'messages' => [
                    'steam' => [],
                    'op' => [],
                ],
                'done' => false,
            ];

            // если существует сессия непотвержденных заказов
            if ($request->session()->has('orders_cart') === true)
            {
                // перебирая все неподтвержденные заказы, искать неподтвержденный заказ по хешу
                foreach ($request->session()->get('orders_cart') as $index => $orderData)
                {
                    // если найден
                    if ($orderData['orderHash'] === $request->_orderHash)
                    {
                        // удалить из сессии
                        $request->session()->forget('orders_cart.'.(string)$index);
                        $response['done'] = true;
                        return response()->json(['done' => $response['done'],]);
                    }
                }
                
                // вывести сообщение "Время на подтверждение заказа истекло. Его больше не существует"
                $response['messages']['op'][] = 'A time for confirming this order is expired. Is not actual data received';
                return response()->json(['messages' => $response['messages']['op']]);
            }
            else
            {
                // вывести сообщение "Время на подтверждение заказа истекло. Его больше не существует"
                $response['messages']['op'][] = 'A time for confirming this order is expired. Is not actual data received';
                return response()->json(['messages' => $response['messages']['op']]);
            }

        })->name('acc-orders-deny');

        /**
         * Get order info by hash of order
         * .com/account/orders/get_order_info
         * @return array
         */
        Route::post('/get_order_info', function(\Illuminate\Http\Request $request) {
            
            $response = [
                'messages' => [
                    'steam' => [],
                    'op' => [],
                ],
                'orderData' => null,
            ];

            // если заказ находится в сессии (проверять по параметру)
            if ($request->_isUnconfirmedOrder == "true")
            {
                if (auth()->user()->orderExistsInSession($request->_orderHash))
                {
                    // вернуть ответ с найденными необходимыми данными о заказае
                    $response['orderData'] = auth()->user()->getUnconfirmedOrderByHash($request->_orderHash);
                    return response()->json(['orderData' => $response['orderData'],]);
                }
                // если в сессии по ключу ('orders_tmp') содержатся данные о заказах
                else
                {
                    // вернуть в качестве ответа сообщение о том, что "возможно, время ожидания принятия заказа истекло"
                    $response['messages']['op'][] = 'A time for confirming this order is expired. Is not actual data received';
                    return response()->json(['messages' => $response['messages']['op']]);
                }
            }
            elseif ($request->_isUnconfirmedOrder == "false")
            {
                // если в базе данных существует запись заказа по токену заказа
                $response['orderData'] = auth()->user()->getConfirmedOrderByHash($request->_orderHash);
                return response()->json(['orderData' => $response['orderData'],]);
            }
            else
            {
                $response['messages']['op'][] = 'Something wrong...';
                return response()->json(['messages' => $response['messages']['op']]);
            }

        })->name('acc-orders-getorderinfo');
    });


    // optionals in account route...
    /**
     * Get backgrounds list from steam inventory
     * .com/account/parse_backgrounds
     * @return array
     */
    Route::post('/get_steam_backgrounds', function(\Illuminate\Http\Request $request) {
        $response = array(
            'messages' => [
                'steam' => [],
            ], // Для хранения информации для пользователя
            'backgrounds' => '', // Для хранения промежуточных результатов
        );
        // Проверка доступа к данным пользователя
        $response['messages']['steam'] = auth()->user()->validateSteamAccount();


        // Если есть сообщения, то отправить их пользователю
        if (count($response['messages']['steam']) !== 0)
        {
            return response()->json(array('messages' => $response['messages'],));
        }


        // Получить список фонов тещкуего пользователя в виде HTML
        $response['backgrounds'] = auth()->user()->getBackgroundsListHtml();

        return response()->json($response);
        
    })->name('get-steam-backgrounds');

});



/**
 * PAYMENTS
 */
Route::prefix('/payments')->group(function() {
    // show variants...
    Route::get('/', 'Payments\PaymentController@showPayments')->name('payments-show');
    Route::post('/', 'Payments\PaymentController@sendPaymentRequest')->name('payments-sendrequest');

    // // yandex money...
    // Route::get('/yamoney', 'Payments\PaymentController@showPaymentForm')->name('payments-postindex');
    // Route::post('/yamoney', 'Payments\PaymentController@sendPaymentRequest')->name('payments-postindex');
});







/**
 * Development
 */

// Fictive accepting/declining of user products
Route::get('/p/{compReqId}-{status}', function(Request $request, $compReqId, $status) {

    $project = [
        'hash' => $projectToken = \Artworch\Modules\User\Account\CompRequest::find($compReqId)->project_token,
    ];
    $project['dir']['relative'] = 'compositions/production/requests/';
    $project['pictures']['preview'] = $project['dir']['relative'] . $projectToken . '/' . $projectToken . '.gif';
    $project['pictures']['freeze'] = $project['dir']['relative'] . $projectToken . '/' . $projectToken . '.png';


    if ($status == 'decline') {
        // если картинки существуют в публичной директории по хешу, то удалить их
        if (Storage::exists('/public/img/compositions/' . $project['hash'] . '.gif') &&
            Storage::exists('/public/img/compositions/' . $project['hash'] . '.png'))
        {
            Storage::move('/public/img/compositions/' . $project['hash'] . '.gif', $project['pictures']['preview']);
            Storage::move('/public/img/compositions/' . $project['hash'] . '.png', $project['pictures']['freeze']);
        }

        // обновление статуса заявки на неутвердительный
        \Artworch\Modules\User\Account\CompRequest::find($compReqId)->update(['accept_status' => '0',]);
        \Artworch\Modules\User\Account\CompRequest::find($compReqId)->composition()->delete();
    } else if ($status == 'accept') {
        // если картинки существуют в директории проекта локального хранилища по хешу, то разместить в публичной директории (storePublicly)
        if (Storage::exists($project['pictures']['preview']) &&
            Storage::exists($project['pictures']['freeze']))
        {
            Storage::move($project['pictures']['preview'], '/public/img/compositions/' . $project['hash'] . '.gif');
            Storage::move($project['pictures']['freeze'], '/public/img/compositions/' . $project['hash'] . '.png');
        }

        // обновление статуса заявки на утвердительный
        \Artworch\Modules\User\Account\CompRequest::find($compReqId)->update(['accept_status' => '1',]);
        \Artworch\Modules\User\Account\CompRequest::find($compReqId)->composition()->create(['expire_at' => Carbon\Carbon::now()->addMonth()]);

        // изменение статус отображения на утвердительный
        \Artworch\Modules\User\Account\CompRequest::find($compReqId)->composition()->update(['view_status' => '1',]);
    }

    dd(\Artworch\Modules\User\Account\CompRequest::find($compReqId)->composition);
});

// Clearing...
Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
	Artisan::call('route:clear');
    return "Кэш очищен.";
});