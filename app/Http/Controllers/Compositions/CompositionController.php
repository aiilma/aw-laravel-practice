<?php

namespace Artworch\Http\Controllers\Compositions;

use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;
use Artworch\Modules\Compositions\Composition;

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
        // создать пустой массив с информацией о карточках композиций
        // если значение параметра номера страницы (R) не равно null И оно больше единицы
        // то записать в массив карточек следующие R * K (где K - количество карточек на странице) композиций отфильтрованных по полю published_date по убыванию
        // иначе - записать в массив карточек последние K карточек
        // отправить на страницу массив информации о карточках
        // dd(['compositionsList' => array('hey', 'xod')]);
        return view('compositions.list', ['compositionsList' => array('hey', 'ho')]);
    }

    public function getCompositionInfo()
    {
        
    }

    public function buyComposition()
    {

    }
}
