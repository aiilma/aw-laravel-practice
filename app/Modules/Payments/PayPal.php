<?php

namespace Artworch\Modules\Payments;

use Omnipay\Omnipay;

/**
 * Class PayPal
 * @package App
 */
class PayPal
{
    /**
     * @return mixed
     */
    public function gateway()
    {
        $gateway = Omnipay::create('PayPal_Express');

        $gateway->setUsername(config('paypal_payment.credentials.username'));
        $gateway->setPassword(config('paypal_payment.credentials.password'));
        $gateway->setSignature(config('paypal_payment.credentials.signature'));
        $gateway->setTestMode(config('paypal_payment.credentials.sandbox'));
        
        return $gateway;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function purchase(array $parameters)
    {
        $response = $this->gateway()
            ->purchase($parameters)
            ->send();

        return $response;
    }

    /**
     * @param array $parameters
     */
    public function complete(array $parameters)
    {
        $response = $this->gateway()
            ->completePurchase($parameters)
            ->send();

        return $response;
    }

    /**
     * @param $amount
     */
    public function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * @param $comp
     */
    public function getCancelUrl($comp)
    {
        return route('paypal.checkout.cancelled', $comp->id);
    }

    /**
     * @param $comp
     */
    public function getReturnUrl($comp)
    {
        return route('paypal.checkout.completed', $comp->id);
    }

    /**
     * @param $comp
     */
    public function getNotifyUrl($comp)
    {
        $env = config('paypal.credentials.sandbox') ? "sandbox" : "live";

        return route('webhook.paypal.ipn', [$comp->id, $env]);
    }
}