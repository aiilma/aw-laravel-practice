<?php

namespace Artworch\Http\Controllers\Payments;

use Artworch\Modules\User\Account\CompRequest;
use Illuminate\Http\Request;
use Omnipay\Omnipay; 
use Artworch\Http\Controllers\Controller;

class CreditCardController extends Controller
{
    
    public function form(Request $request, $comp_id = null)
    {

    }


    public function checkout(Request $request, $comp_id)
    {

    }
}