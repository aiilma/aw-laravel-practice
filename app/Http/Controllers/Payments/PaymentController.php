<?php

namespace Artworch\Http\Controllers\Payments;

use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Возвращает HTML список доступных платежных систем
     *
     * @return string
     */
    public function showPayments(Request $request)
    {
        return view('systems.payments.list');
    }

    /**
     * Выполнение транзакции пополнения баланса пользователя
     *
     * @return string
     */
    public function sendPaymentRequest(Request $request)
    {
        
    }
}
