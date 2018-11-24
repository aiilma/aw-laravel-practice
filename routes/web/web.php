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
    });


    // optionals in account route...
    /**
     * Get backgrounds list from steam inventory
     * .com/account/parse_backgrounds
     * @return array
     */
    Route::post('/get_steam_backgrounds', function(Request $request) {
        $response = array(
            'messages' => [
                'steam' => [],
            ], // Для хранения информации для пользователя
            'backgrounds' => '', // Для хранения промежуточных результатов
        );
        // Проверка доступа к данным пользователя
        $response['messages']['steam'] = auth()->user()->validateSteamAccountSteamAccount();


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
Route::get('/p/{compid}-{status}', function(Request $request, $compid, $status) {

    $project = [
        'hash' => $projectToken = \Artworch\Modules\User\Account\CompRequest::find($compid)->project_token,
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
        \Artworch\Modules\User\Account\CompRequest::find($compid)->update(['accept_status' => '0',]);
        \Artworch\Modules\User\Account\CompRequest::find($compid)->composition()->delete();
    } else if ($status == 'accept') {
        // если картинки существуют в директории проекта локального хранилища по хешу, то разместить в публичной директории (storePublicly)
        if (Storage::exists($project['pictures']['preview']) &&
            Storage::exists($project['pictures']['freeze']))
        {
            Storage::move($project['pictures']['preview'], '/public/img/compositions/' . $project['hash'] . '.gif');
            Storage::move($project['pictures']['freeze'], '/public/img/compositions/' . $project['hash'] . '.png');
        }

        // обновление статуса заявки на утвердительный
        \Artworch\Modules\User\Account\CompRequest::find($compid)->update(['accept_status' => '1',]);
        \Artworch\Modules\User\Account\CompRequest::find($compid)->composition()->create(['expire_at' => Carbon\Carbon::now()->addMonth()]);

        // изменение статус отображения на утвердительный
        \Artworch\Modules\User\Account\CompRequest::find($compid)->composition()->update(['view_status' => '1',]);
    }

    dd(\Artworch\Modules\User\Account\CompRequest::find($compid)->composition);
});

// Clearing...
Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
	Artisan::call('route:clear');
    return "Кэш очищен.";
});