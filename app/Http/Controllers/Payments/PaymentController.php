<?php

namespace Artworch\Http\Controllers\Payments;

use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Возвращает главную страницу выбора платежных систем
     *
     * @return string
     */
    public function indexIn(Request $request)
    {
        return view('systems.payments.index-in');
    }

    /**
     * Возвращает главную страницу выбора платежных систем
     *
     * @return string
     */
    public function indexOut(Request $request)
    {
        return view('systems.payments.index-out');
    }

    /**
     * Возвращает историю платежей
     *
     * @param Request $request
     * @return string
     */
    public function history(Request $request)
    {
        // 
    }
}
