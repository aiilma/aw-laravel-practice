<?php

namespace Artworch\Http\Controllers\Payments;

use Artworch\Modules\User\Account\CompRequest;
use Artworch\Modules\Payments\PayPal;
use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;

class PayPalController extends Controller
{
    /**
     * @param Request $request
     */
    public function form(Request $request, $comp_id = null)
    {
        $comp_id = $comp_id ?: encrypt(1);

        $comp = CompRequest::findOrFail(decrypt($comp_id));
        return view('form', compact('comp'));
    }

    /**
     * @param $comp_id
     * @param Request $request
     */
    public function checkout(Request $request, $comp_id)
    {
        $comp = CompRequest::findOrFail(decrypt($comp_id));

        $paypal = new PayPal;


        $response = $paypal->purchase([
            'amount' => $paypal->formatAmount($comp->custom_price),
            'transactionId' => 5,
            'currency' => 'USD',
            'cancelUrl' => $paypal->getCancelUrl($comp),
            'returnUrl' => $paypal->getReturnUrl($comp)
        ]);
        
        
        if ($response->isRedirect()) {
            $response->redirect();
        }
        // dd($response->getMessage());

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }

    /**
     * @param $comp_id
     * @param Request $request
     * @return mixed
     */
    public function completed(Request $request, $comp_id)
    {
        $comp = CompRequest::findOrFail($comp_id);

        $paypal = new PayPal;

        $response = $paypal->complete([
            'amount' => $paypal->formatAmount($comp->custom_price),
            'transactionId' => 5,
            'currency' => 'USD',
            'cancelUrl' => $paypal->getCancelUrl($comp),
            'returnUrl' => $paypal->getReturnUrl($comp),
            'notifyUrl' => $paypal->getNotifyUrl($comp),
        ]);

        if ($response->isSuccessful()) {
            $comp->update(['transaction_id' => $response->getTransactionReference()]);

            return redirect()->route('app.home', encrypt($comp_id))->with([
                'message' => 'You recent payment is sucessful with reference code ' . $response->getTransactionReference(),
            ]);
        }

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }

    /**
     * @param $comp_id
     */
    public function cancelled($comp_id)
    {
        $comp = CompRequest::findOrFail($comp_id);

        return redirect()->route('app.home', encrypt($comp_id))->with([
            'message' => 'You have cancelled your recent PayPal payment !',
        ]);
    }

    /**
     * @param $comp_id
     * @param $env
     */
    public function webhook($comp_id, $env)
    {
        // to do with next blog post
    }
}
