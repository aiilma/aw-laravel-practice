<?php

namespace Artworch\Http\Controllers\Compositions;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Artworch\Http\Controllers\Controller;
use Artworch\Modules\Compositions\Composition;
use Input;
use Form;

class CompositionController extends Controller
{
    /**
     * Метод выводит список композиций для отображения на страницах главного листинга
     *
     * @param Request $request
     * @return string
     */
    public function getListCompositions(Request $request)
    {
        // пустой массив с информацией о карточках композиций
        $compList = [];
        // записать в массив карточек следующие R * K (где K - количество карточек на странице) композиций отфильтрованных по полю published_date по убыванию
        $compList = Composition::orderBy('published_date', 'desc')->paginate(config('compositions.max_cards_per_page'), ['*'], 'list')->onEachSide(1);
        // отправить на страницу массив информации о карточках
        return view('compositions.list', ['compositions' => $compList]);
    }

    /**
     * Метод извлекает информацию о композиции для заполнения формы заказа пользователем
     *
     * @return string
     */
    public function getCompositionInfo(Request $request)
    {
        $compId = $request->composition_id;
        $dataForSelect = [ // необходимая для извлечения и отправки пользователю информация о композиции
            'id', 'title',
            'freeze_picture', 'preview_picture',
            'custom_price', 'published_date'
        ];

        // по id композиции вытащить следующую информацию из базы данных: title, freeze_picture, preview_picture, custom_price, published_date
        $compDataForm = Composition::select($dataForSelect)->where('id', $compId)->get()[0];
        return view('compositions.form', ['compositionDataForm' => $compDataForm]);

        // отправить на страницу с формой композиции по ID массив информации о композиции
    }

    /**
     * Метод обработки данных формы пользователя при покупке композиции
     *
     * @return void
     */
    public function buyComposition(Request $request)
    {
        return $request;
        // вытащить данные
        // валидировать данные
        // отправить данные в БД
        // если произошла ошибка, то вернуть статус ошибки
        // иначе - редирект на страницу листинга заказов
    }
}
