<?php

namespace Artworch\Http\Controllers\User\Auth;

use Artworch\Modules\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artworch\Http\Controllers\Controller;

class VerifyController extends Controller
{
    /**
     * Verify the user with a give token
     * 
     * @param string $token
     * 
     * @return Response
     * 
     */
    public function verify($token)
    {
        // token update
        $user = User::where('token', $token)->firstOrFail();
        $user->update(['token' => null, 'email_verified_at' => Carbon::now()]);
        // login
        auth()->guard()->login($user);

        return redirect()
                        ->route('acc-home')
                        ->with('verify_state', 'Account verified!');
    }
}
