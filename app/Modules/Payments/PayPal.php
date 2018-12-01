<?php

namespace Artworch\Modules\Payments;

use Omnipay\Omnipay;
use Validator;

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
    public function getCancelUrl($encryptAmount, $encryptMethod)
    {
        return route('payments-in-paypal-canceled', [$encryptAmount, $encryptMethod]);
    }

    /**
     * @param $comp
     */
    public function getReturnUrl($encryptAmount, $encryptMethod)
    {
        return route('payments-in-paypal-completed', [$encryptAmount, $encryptMethod]);
    }

    /**
     * @param $comp
     */
    public function getNotifyUrl()
    {
        $env = config('paypal.credentials.sandbox') ? "sandbox" : "live";

        return route('payments-in-paypal-webhook', [$env]);
    }

    /**
     * Валидация входных данных при пополнении баланса пользователя на сайте
     *
     * @param [type] $data
     * @return object
     */
    public static function validateInputsOnFormat($data)
    {
        $validateResult = Validator::make($data,
        [
            '_pgmethod' => 'required|string|in:paypal,qiwi',
            '_amount' => 'required|numeric|lte:4',
            '_amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('An amount of money must not be less or equal to zero');
                    }
                },
            ],
        ],[
            '_pgmethod.required' => 'Please, choose one of availables payment method',
            '_pgmethod.string' => 'Bad format of data',
            '_pgmethod.in' => 'The specified payment method does not exists',

            '_amount.required' => 'Please, type an amount of money',
            '_amount.numeric' => 'Bad format of data'
        ]);

        return $validateResult->messages();

    }
}