<?php

namespace Artworch\Http\Controllers\Payments;

use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;
use Artworch\Modules\Payments\PaymentTransaction;
use Artworch\Modules\Payments\PayPal;
use DB;

class PayPalController extends Controller
{
    /**
     * @param Request $request
     */
    public function indexIn(Request $request)
    {
        return view('systems.payments.paypal.in');
    }


    /**
     * Выполнение транзакции пополнения баланса пользователя
     *
     * @return string
     */
    public function checkIn(Request $request)
    {
        $request->request->add(['_pgmethod' => 'paypal']);
        
        // валидировать входные данные объекта $request
        $pmFormatResult = PayPal::validateInputsOnFormat($request->all());


        $paypal = new PayPal;

        $response = $paypal->purchase([
            'amount' => $paypal->formatAmount($request->_amount),
            'transactionId' => DB::table('payment_transactions')->latest('transaction_id')->first()->transaction_id + 1,
            'currency' => 'USD',
            'cancelUrl' => $paypal->getCancelUrl(encrypt($request->_amount), encrypt($request->_pgmethod)),
            'returnUrl' => $paypal->getReturnUrl(encrypt($request->_amount), encrypt($request->_pgmethod))
        ]);

        if ($response->isRedirect()) {
            $response->redirect();
        }
        
        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }


    /**
     * Признак успешной денежной транзакции
     *
     * @return string
     */
    public function completedIn(Request $request, $encryptAmount, $encryptMethod)
    {
        $decryptAmount = decrypt($encryptAmount);
        $decryptMethod = decrypt($encryptMethod);
        
        $paypal = new PayPal;

        $response = $paypal->complete([
            'amount' => $paypal->formatAmount($decryptAmount),
            'transactionId' => DB::table('payment_transactions')->latest('transaction_id')->first()->transaction_id + 1,
            'currency' => 'USD',
            'cancelUrl' => $paypal->getCancelUrl($encryptAmount, $encryptMethod),
            'returnUrl' => $paypal->getReturnUrl($encryptAmount, $encryptMethod),
            'notifyUrl' => $paypal->getNotifyUrl(),
        ]);

        $transaction = new PaymentTransaction;
        $transaction->user_id = auth()->user()->id;
        $transaction->amount = $decryptAmount;
        $transaction->method = $decryptMethod;
        $transaction->type = 'i';
        $transaction->confirm_status = 0;

        // SUCCESS
        if ($response->isSuccessful()) {

            // пополнить баланс пользовател в БД
            auth()->user()->balance += $decryptAmount;
            auth()->user()->save();

            $transaction->transaction_code = $response->getTransactionReference();
            $transaction->confirm_status = 1;
            $transaction->save();

            // вернуть сообщение об успешной транзакции
            return redirect()->route('payments-in-paypal-index')->with([
                'message' => 'You recent payment is sucessful with reference code ' . $response->getTransactionReference(),
            ]);
        }

        $transaction->message = $response->getMessage();
        $transaction->save();

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }

    /**
     * Признак ошибки выполнения транзакции
     *
     * @return string
     */
    public function canceledIn(Request $request, $encryptAmount, $encryptMethod)
    {
        $message = 'You have cancelled your recent PayPal payment!';

        $decryptAmount = decrypt($encryptAmount);
        $decryptMethod = decrypt($encryptMethod);

        $transaction = new PaymentTransaction;
        $transaction->user_id = auth()->user()->id;
        $transaction->amount = $decryptAmount;
        $transaction->method = $decryptMethod;
        $transaction->type = 'i';
        $transaction->confirm_status = 0;
        $transaction->message = $message;
        $transaction->save();

        return redirect()->route('payments-in-paypal-index')->with([
            'message' => $message,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param [type] $env
     * @return void
     */
    public function webhook($env)
    {
        // to do with next blog post
    }

// -------------------------

    /**
     * @param Request $request
     */
    public function indexOut(Request $request)
    {
        return view('systems.payments.paypal.out');
    }

    /**
     * Выполнение запроса на вывод денежных средств из текущего баланса пользователя
     *
     * @return string
     */
    public function checkOut()
    {
        // After Step 1
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                config('paypal_payment.credentials.client_id'),     // ClientID
                config('paypal_payment.credentials.secret')         // ClientSecret
            )
        );

        $payouts = new \PayPal\Api\Payout();
        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())->setEmailSubject("You have a Payout!");
        $senderItem = new \PayPal\Api\PayoutItem();
        $senderItem->setRecipientType('Email')->setNote('Thanks for your patronage!')->setReceiver('awbuyer@mail.ru')->setSenderItemId(uniqid())->setAmount(new \PayPal\Api\Currency('{
                                "value":"700.00",
                                "currency":"USD"
                            }'));
        $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);
        $request = clone $payouts;
        try {
            $output = $payouts->create(array('sync_mode' => 'false'), $apiContext);
            // $output = $payouts->createSynchronous($apiContext);
        } catch (\Exception $ex) {
            echo "PayPal Payout GetData:<br>". $ex->getData() . "<br><br>";
            exit(1);
        }

        return $output;



    }

    
}
