<?php

namespace Artworch\Modules\User;

use Artworch\Notifications\VerifyEmail;
use Artworch\Modules\User\Account\CompRequest;
use Artworch\Modules\Compositions\Composition;
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
        'token', 'steamid', 'avatar',
        'balance', 'remember_token', 
        'status', 'email_verified_at',
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

    /**
     * Relation to composition requests; one to many
     *
     * @return void
     */
    public function compRequests()
    {
        return $this->hasMany(CompRequest::class, 'author_id');
    }


    /**
     * Relation to compositions; one to many
     *
     * @return void
     */
    public function compositions()
    {
        return $this->hasManyThrough(Composition::class, CompRequest::class, 'author_id', 'comp_request_id');
    }
}
