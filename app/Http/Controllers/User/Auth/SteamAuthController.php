<?php

namespace Artworch\Http\Controllers\User\Auth;

use Illuminate\Http\Request;
use Auth;
use Session;
use Artworch\Modules\User\User;
use Artworch\Http\Controllers\Controller;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Validator;


class SteamAuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/account';

    /**
     * AuthController constructor.
     * 
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Redirect the user to the authentication page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToSteam()
    {
        return $this->steam->redirect();
    }

    /**
     * Get user info and log in
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {

                $response = [
                    'messages' => null,
                ];

                $response['messages'] = Validator::make([
                    'steamid' => $info->steamID64,
                ],[
                    'steamid' => 'unique:users',
                ],[
                    'steamid.unique' => 'The '.$info->steamID64.' is already binded',
                ])->messages();
        

                if (count($response['messages']) > 0)
                {
                    return redirect()->route('acc-settings')->withErrors($response['messages']);
                }

                $this->steamBind($info);
                return redirect($this->redirectURL); // redirect to site
            }
        }
        return $this->redirectToSteam();
    }

    /**
     * Getting user by info or created if not exists
     *
     * @param $info
     * @return User
     */
    protected function findOrNewUser($info)
    {
        $user = User::where('steamid', $info->steamID64)->first();

        if (!is_null($user)) {
            return $user;
        }

        return User::create([
            'username' => $info->personaname,
            'avatar' => $info->avatarfull,
            'steamid' => $info->steamID64
        ]);
    }

    /**
     * Метод синхронизации данных (привязки) Steam аккаунта к аккаунту пользователя на сайте
     *
     * @return void
     */
    public function steamBind($info)
    {
        Auth::user()->steamid = $info->steamID64;
        Auth::user()->avatar = substr($info->avatarfull, 69); // removed as a static string https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/
        Auth::user()->save();
    }

}
