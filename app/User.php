<?php

namespace Artworch;

use Artworch\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
        'email_verified_at', 'token', 'steamid',
        'avatar', 'balance', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'token',
    ];


    /**
     * Returns true if user verified
     * 
     * @return bool
     */
    public function verified()
    {
        return $this->token === null;
    }


    /**
     * Send the user a verification email
     * 
     * @return void
     */
    public function sendVerificationEmail()
    {
        $this->notify(new VerifyEmail($this));
    }
}
