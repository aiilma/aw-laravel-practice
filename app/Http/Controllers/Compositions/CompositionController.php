<?php

namespace Artworch\Http\Controllers\Compositions;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Artworch\Http\Controllers\Controller;
use Artworch\Modules\Compositions\Composition;
use Artworch\Modules\User\Account\CompRequest;
use Artworch\Modules\User\User;
use Illuminate\Validation\Rule;
use Input, Form, Validator, Session;

class CompositionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['getListCompositions']);
    }

    /**
     * Метод выводит список композиций для отображения на страницах главного листинга
     *
     * @param Request $request
     * @return string
     */
    public function getListCompositions(Request $request)
    {
        $compList = [];
        // записать в массив следующие R * K (где K - количество карточек на странице) композиций отфильтрованных по полю published_at по убыванию
        $compList = Composition::orderBy('published_at', 'desc')
                                ->where('view_status', '=', '1')
                                ->paginate(config('compositions.max_cards_per_page'), ['*'], 'list')
                                ->onEachSide(1);
        
        return view('systems.compositions.list', ['compositions' => $compList]);
    }

    /**
     * Метод извлекает информацию о композиции для заполнения формы заказа пользователем
     *
     * @return string
     */
    public function getCompositionInfo(Request $request, $compId)
    {
        // Проверка доступа к данным пользователя
        $userScanResults = auth()->user()->validateSteamAccount();

        // отправить на страницу с формой композиции по ID массив информации о композиции
        return view('systems.compositions.form', ['compDataForm' => Composition::findOrFail($compId), 'messages' => $userScanResults,]);
    }

    /**
     * Метод обработки данных формы пользователя при покупке композиции
     *
     * @return void
     */
    public function buyComposition(Request $request)
    {
        $request->request->add(['_userBalance' => auth()->user()->balance]);
        $request->request->add(['_ordersCount' => ($request->session()->get('orders_cart') !== null) ? count($request->session()->get('orders_cart')) : 0,]);
        $request->request->add(['_orderHash' => str_random(255),]);

        $response = array(
            'messages' => [
                'transaction' => [],
                'steam' => [],
            ], // Для хранения информации для пользователя
            'preparedOrderInfo' => [
                'orderHash' => $request->_orderHash,
                'compHash' => $request->_compHash,
                'visualization' => $request->_visualization,
                'background' => $request->_background,
            ],
        );

        $buyCompResultData = Validator::make($request->all(), [ // Статусы доступа к данным пользователя
            '_compHash' => 'exists:comp_requests,project_token',
            '_visualization' => [
                                    'required',
                                    'in:0,1',
                                    Rule::exists('comp_requests', 'visualization')
                                        ->where(function ($query) use ($request) {
                                            $query->where('project_token', $request->_compHash);
                                    }),
            ],
            '_background' => 'required|url',
            '_userBalance' => 'numeric|gte:'.CompRequest::where('project_token', '=', $request->_compHash)->first()->custom_price,
            '_ordersCount' => 'lte:'.(integer)config('orders.max_orders_per_time'),
        ], [
            '_compHash.exists' => 'The product does not exists',
            '_visualization.required' => 'The visualization is required',
            '_visualization.in' => 'The visualization may have only 2 possible values',
            '_visualization.exists' => 'Invalid visualization specified for current product',
            '_background.required' => 'The background is required',
            '_background.url' => 'Invalid URL of background',
            '_userBalance.numeric' => 'Your balance must have a numeric format',
            '_userBalance.gte' => 'Is not enough of cash on your balance',
            '_ordersCount.lte' => 'Too many orders in queue session',
        ]);
        

        $response['messages']['transaction'] = $buyCompResultData->messages();
        $response['messages']['steam'] = auth()->user()->validateSteamAccount();


        if (count($response['messages']['transaction']) == 0 && count($response['messages']['steam']) == 0)
        {
            if (!$request->session()->has('orders_cart'))
            {
                $request->session()->put('orders_cart', []);
            }

            $request->session()->push('orders_cart', $response['preparedOrderInfo']);
        }
    
        return response()->json(['messages' => $response['messages'],]);

    }
}
