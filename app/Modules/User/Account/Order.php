<?php

namespace Artworch\Modules\User\Account;

use Illuminate\Database\Eloquent\Model;
use Artworch\Modules\User\Account\CompRequest;
use Session;

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


    /**
     * Транзакция вычисления денежного баланса у обеих сторон и добавления информации о заказе в БД
     *
     * @param [array] $data
     * @return array
     */
    public static function add($data)
    {
            // ФИНАНСЫ
            // вычесть сумму денежных средств из баланса пользователя (обновить значение в БД)
            $projectPrice = CompRequest::where('project_token', '=', $data['_compHash'])->first()->custom_price;
            auth()->user()->balance -= $projectPrice;
            auth()->user()->save();
            // прибавить сумму денежных средств на денежный баланс автора (обновить значение в БД)
            $author = CompRequest::where('project_token', '=', $data['_compHash'])->first()->user;
            $author->balance += $projectPrice;
            $author->save();


            // ИНФОРМАЦИЯ
            // занести информацию о заказе пользователя в БД (из сессии + еще по необходимости->см.схему)
            $order = new self;
            $order->customer_id = auth()->user()->id;
            $order->project_ref = $data['_compHash'];
            $order->order_token = $data['_orderHash'];
            $order->status = '1';
            $order->user_data = json_encode(self::getOrderDataByHash($data['_orderHash']));
            $order->save();


            // удалить сессию по хешу заказа
            foreach (Session::get('orders_cart') as $index => $orderData)
            {
                if ($orderData['orderHash'] === $data['_orderHash'])
                {
                    Session::forget('orders_cart.'.(string)$index);
                }
            }

            return $order;
    }

    /**
     * Возвращает данные заказа пользователя по хешу
     *
     * @param [string] $hash
     * @return array
     */
    public static function getOrderDataByHash($hash)
    {
        $data = [
            'default' => [
                'visualization' => null,
                'background' => null,
            ],
            'optional' => [
                'inputs' => [],
            ],
        ];

        // получить данные о заказе напрямую из сессии
        foreach (Session::get('orders_cart') as $index => $orderData)
        {
            // если найден
            if ($orderData['orderHash'] === $hash)
            {
                $data['default']['visualization'] = $orderData['visualization'];
                $data['default']['background'] = $orderData['background'];
            }
        }

        return $data;
    }

    /**
     * Возвращает статус заказа в виде строки
     *
     * @return string
     */
    public function getStatusOfAttr()
    {
        switch ($this->status) {
            case '1':
                return 'wait';
                break;
            case '2':
                return 'process';
                break;
            case '3':
                return 'export';
                break;
            case '4':
                return 'done';
                break;
            
            default:
                return '';
                break;
        }
    }


    /**
     * Возвращает Html для вставки в качестве статуса заказа
     *
     * @return string
     */
    public function getStatusHtml()
    {
        switch ($this->status) {
            case '1':
                return '<span>Awaiting...</span>';
                break;
            case '2':
                return '<span>Processing...</span>';
                break;
            case '3':
                return '<span>Exporting...</span>';
                break;
            case '4':
                return '<a class="aw-link" href="#" role="button">Download</a>';
                break;
            
            default:
                return '<span>undefined</span>';
                break;
        }
    }


    /**
     * Возвращает html заказа в соответствие его полям и аттрибутам
     *
     * @return string
     */
    public function getHtml()
    {
        return  '
                <li class="row col-12 row__user__current__order" data-order-status="'.$this->getStatusOfAttr().'">
                  <div class="col-3 col-xl-3 user__order__cell">
                    <a class="aw-link" href="#" role="button">'.$this->compRequest->title.'</a>
                  </div>
                  <div class="col-3 col-xl-3 user__order__cell">
                    <button class="aw-btn aw__btn__check__order__info" data-toggle="modal" data-target="#orderDataModalWrapper" data-link="'.substr(route('acc-orders-getorderinfo'), strlen(url('/'))).'">
                      Check
                    </button>
                  </div>
                  <div class="col-3 col-xl-3 user__order__cell">
                    <p class="order__cell__timedate">'.$this->updated_at->format('d.m.Y').'</p>
                    <p class="order__cell__timedate">'.$this->updated_at->format('H:i:s').'</p>
                  </div>
                  <div class="col-3 col-xl-3 user__order__cell">
                    <span>'.$this->getStatusHtml().'</span>
                  </div>
                  <input type="hidden" name="_compHash" value="'.$this->project_ref.'">
                  <input type="hidden" name="_orderHash" value="'.$this->order_token.'">
                </li>
                ';
    }

    // TODO: метод получения данных о заказе пользователя (поле user_data в представлении массива)

    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Relation to user; belongs to one
     *
     * @return void
     */
    public function compRequest()
    {
        return $this->belongsTo(CompRequest::class, 'project_ref', 'project_token');
    }
}

