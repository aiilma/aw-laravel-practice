<?php

namespace Artworch\Modules\User;

use Artworch\Notifications\VerifyEmail;
use Artworch\Modules\User\Account\Order;
use Artworch\Modules\User\Account\CompRequest;
use Artworch\Modules\Compositions\Composition;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator, Cache;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
        'token', 'steamid', 'avatar',
        'balance', 'remember_token', 
        'status', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'token',
    ];




    /**
     * Возвращает html строку денежного баланса пользователя
     *
     * @return string
     */
    public function getBalanceHtml()
    {
        return '
                <a id="navbarDropdown" class="ui aw-nav-link aw-dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="$ '.number_format($this->balance, 2).'">
                    $ '.number_format($this->balance, 2).'
                </a>

                <div id="awAccountDropdownBalance" class="dropdown-menu dropdown-menu-right text-center" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="'.route('payments-in-index').'">'.__('Check-In').'</a>
                    <a class="dropdown-item" href="'.route('payments-out-index').'">'.__('Check-Out').'</a>
                </div>
                ';
    }


    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Steam
    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Возвращает статус доступа к Steam аккаунту текущего пользователя
     *
     * @return array
     */
    public function validateSteamAccount()
    {
        $user =  [ // Статусы доступа к данным пользователя
            'is_steamid' => $this->isBindedSteam(),
            'is_public' => [
                'profile' => $this->isPublicSteamAccount(), // value from api; ONLINE
                'inventory' => $this->isPublicSteamInv(), // value from api; ONLINE
            ],
            'is_items' => [
                'inventory' => $this->isItemsInSteamInv(),
            ]
        ];

        $userSteamAccount = Validator::make($user, [
            'is_steamid' => 'accepted',
            'is_public.profile' => $this->isBindedSteam() ? 'accepted' : '', // not in; for online
            'is_public.inventory' => $this->isPublicSteamAccount() ? 'accepted' : '', // not in; for online
            'is_items.inventory' => $this->isPublicSteamInv() ? 'accepted' : '',
            ], [
            'is_steamid.accepted' => 'Please, make sure your SteamID has been bind to this account',
            'is_public.profile.accepted' => 'Please, make sure your steam profile is public',
            'is_public.inventory.accepted' => 'Please, make sure your steam inventory is public',
            'is_items.inventory.accepted' => 'Unfortunately, no backgrounds in your inventory',
        ]);

        return $userSteamAccount->messages();
    }


    /**
     * Возвращает html строку фонов пользователя из инвентаря для вывода в <ul>
     *
     * @return string
     */
    public function getBackgroundsListHtml()
    {
        $backgroundsSteamHtml = '';


        foreach ($this->getBackgroundsFromSteamInv() as $key => $bgInfo)
        {
            $backgroundsSteamHtml .= '<li class="userbg-item d-inline-block">
                                        <button type="button">
                                            <img class="img-fluid" src="'.$bgInfo['img'].'" width="96" height="96">
                                        </button>
                                      </li>';
        }


        return $backgroundsSteamHtml;
    }


    /**
     * Возвращает массив URL адресов на каждый из уникальных фонов тещкуего пользователя, если инвентарь не пустой
     *
     * @return array
     */
    public function getBackgroundsFromSteamInv()
    {
        // фейковый массив
        $backgroundsFromSteamInv = [
            'all' => [],
            'middlewareNames' => [

            ], // ключи уникальных фонов из ячейки all
            'unique' => [],
        ];


        // Если инвентарь не пустой...
        if ($this->isItemsInSteamInv())
        {
            $userInventory = $this->parseSteamInv();


            foreach ($userInventory['rgInventory'] as $key => $value) {
                $id = $value['classid'].'_'.$value['instanceid'];
                if (!preg_match('/(Background)/', $userInventory['rgDescriptions'][$id]['type'])) continue;
                $name = $userInventory['rgDescriptions'][$id]['market_hash_name'];
                $img = 'https://steamcommunity-a.akamaihd.net/economy/image/'.$userInventory['rgDescriptions'][$id]['icon_url'];
    
                $backgroundsFromSteamInv['all'][] = array(
                    'assetid' => $value['id'],
                    'img' => $img,
                    'name' => $name);

                $backgroundsFromSteamInv['middlewareNames'][] = $name;
            }
            

            // Только уникальные фоны
            foreach (array_unique($backgroundsFromSteamInv['middlewareNames']) as $index => $name)
            {
                $backgroundsFromSteamInv['unique'][] = $backgroundsFromSteamInv['all'][$index];
            }
        }

        
        return $backgroundsFromSteamInv['unique'];
    }


    /**
     * Возвращает true, если steamid привязан к аккаунту пользователя
     *
     * @return boolean
     */
    public function isBindedSteam()
    {
        return $this->steamid != null;
    }

    /**
     * Возвращает true, если steam аккаунт пользователя является публичным
     *
     * @return boolean
     */
    public function isPublicSteamAccount()
    {
        // получить значение статуса приватности профиля по API
        return ($this->getUserInfoBySteamID()['communityvisibilitystate'] === 3) ? true : false;
    }

    /**
     * Возвращает true, если steam инвентарь пользователя является публичным
     *
     * @return boolean
     */
    public function isPublicSteamInv()
    {
        // получить значение статуса приватности профиля по API
        if (!$this->isPublicSteamAccount())
        {
            return false;
        }

        
        $userInventory = $this->parseSteamInv();
        
        return $userInventory['success'] ? true : false;
    }

    /**
     * Возвращает true, если steam инвентарь пользователя пустой
     *
     * @return boolean
     */
    public function isItemsInSteamInv()
    {
        $countItems = 0;


        if ($this->isPublicSteamInv() === true)
        {
            $backgrounds = [];
            

            // если фоны были закешированы, то получить из кеша,
            // иначе - из ответа запроса к Steam API
            $userInventory = $this->parseSteamInv();


            foreach ($userInventory['rgInventory'] as $key => $value) {
                $id = $value['classid'].'_'.$value['instanceid'];
                if (!preg_match('/(Background)/', $userInventory['rgDescriptions'][$id]['type'])) continue;
                $name = $userInventory['rgDescriptions'][$id]['market_hash_name'];
                $img = 'https://steamcommunity-a.akamaihd.net/economy/image/'.$userInventory['rgDescriptions'][$id]['icon_url'];
    
                $backgrounds[] = array(
                    'assetid' => $value['id'],
                    'img' => $img,
                    'name' => $name);
            }

            // фейковый массив
            $countItems = count($backgrounds);
        }


        return ($countItems > 0) ? true : false;
    }

    /**
     * Getting user info by SteamID
     *
     * @return array
     */
    public function getUserInfoBySteamID()
    {
        $userSummaries['response']['players'][0] = null;


        if ($this->isBindedSteam())
        {
            $userSummariesUrl = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.config('steam-auth.api_key').'&steamids='.$this->steamid;
            $userSummariesJson = file_get_contents($userSummariesUrl);
            $userSummaries = json_decode($userSummariesJson, true);
        }
        

        return $userSummaries['response']['players'][0];
    }

    /**
     * Возвращает инвентарь в качестве массива
     *
     * @return array
     */
    private function parseSteamInv()
    {
        $userInventoryUrl = 'https://steamcommunity.com/profiles/'.$this->steamid.'/inventory/json/753/6/2';

        
        if (Cache::has('aw_steam_inv')) {
            $userInventoryJson = Cache::get('aw_steam_inv');
        } else {
            $userInventoryJson = file_get_contents($userInventoryUrl);
            Cache::put('aw_steam_inv', $userInventoryJson, 5); // 5 minutes
        }


        $userInventory = json_decode($userInventoryJson, true);
        return $userInventory;
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Composition Requests
    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Валидация на лимит по количеству продукции текущего пользователя
     * Возвращает не пустой массив ошибочных сообщений, если было отправлено больше установленного сервером количества заявок
     *
     * @return array
     */
    public function validateOnSendProductionRequest()
    {
        return Validator::make([
                    'userProdCount' => auth()->user()->compRequests->count(),
                ], [
                    'userProdCount' => 'lte:'.(integer)config('production.max_requests_per_time'),
                ], [
                    'userProdCount.lte' => 'You can\'t send requests anymore. Max count of any production activity for your account (including your products on sale) is equals to 5',
                ])->messages();
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Orders
    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------


    /**
     * Возвращает true, если заказ существует в БД или сессии текущего пользователя
     *
     * @param [string] $orderHash
     * @return bool
     */
    public function orderExistsInSession($orderHash)
    {
        // проверить данные о заказе напрямую, то есть по ключу сессии
        foreach (session('orders_cart') as $index => $orderData)
        {
            // если найден
            if ($orderData['orderHash'] === $orderHash)
            return true;
        }
        return false;
    }

    /**
     * Возвращает непотвержденный заказ по токену из временного хранилища (сессии)
     *
     * @param [array] $orderHash
     * @return array
     */
    public function getUnconfirmedOrderByHash($orderHash)
    {
        $data = [];

        foreach (session('orders_cart') as $index => $orderData)
        {
            // если заказ существуют (параметр _orderHash объекта $request есть в массиве)
            if ($orderData['orderHash'] === $orderHash)
            {   
                $data = $orderData;
            }    
        }
        
        // вернуть ответ с найденными необходимыми данными о заказае
        return $data;
    }

    /**
     * Возвращает потвержденный заказ по токену из хранилища (базы данных)
     *
     * @param [array] $orderHash
     * @return json
     */
    public function getConfirmedOrderByHash($orderHash)
    {
        $data = [
            'visualization' => null,
            'background' => null,
        ];

        $order = json_decode(Order::where('order_token', '=', $orderHash)->first()->user_data, true);
        $data['visualization'] = $order['default']['visualization'];
        $data['background'] = $order['default']['background'];

        return $data;
    }

    /**
     * Валидация прав пользователя покупать композицию
     * Возвращает не пустой массив ошибочных сообщений, если покупать нельзя
     *
     * @param [string] $compHash
     * @return array
     */
    public function validateUserPermissionsToBuyComposition($compHash)
    {
        return Validator::make([
                    'userBalance' => auth()->user()->balance,
                    'userOrdersCount' => auth()->user()->orders->count(),
                ], [
                    'userBalance' => 'numeric|gte:'.CompRequest::where('project_token', '=', $compHash)->first()->custom_price,
                    'userOrdersCount' => 'lt:'.(integer)config('orders.max_orders_per_time'),
                ], [
                    'userBalance.numeric' => 'Your balance must have a numeric format',
                    'userBalance.gte' => 'Is not enough of cash on your balance',
                    'userOrdersCount.lt' => 'You can\'t buy compositions while it is limited to 5',
                ])->messages();
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Mail
    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Returns true if user verified
     * 
     * @return boolean
     */
    public function verified()
    {
        return $this->token === null;
    }

    /**
     * Send the user a verification email
     * 
     * @return void
     */
    public function sendVerificationEmail()
    {
        $this->notify(new VerifyEmail($this));
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Relation to composition requests; one to many
     *
     * @return void
     */
    public function compRequests()
    {
        return $this->hasMany(CompRequest::class, 'author_id');
    }


    /**
     * Relation to compositions; one to many
     *
     * @return void
     */
    public function compositions()
    {
        return $this->hasManyThrough(Composition::class, CompRequest::class, 'author_id', 'comp_request_id');
    }

    /**
     * Relation to orders; has many orders
     *
     * @return void
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
