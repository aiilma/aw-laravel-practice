<?php

namespace Artworch\Http\Controllers\User\Account\Account;

use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;

class AccountSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('systems.user.account.account.settings');
    }
}
