<?php

namespace Artworch\Modules\User\Account;

use Illuminate\Database\Eloquent\Model;
use Artworch\Modules\Compositions\Composition;
use Artworch\Modules\User\User;

class CompRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'custom_price','visualization', 'inputs',
        'project_token', 'accept_status', 'author_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Relation to composition; one to one
     *
     * @return void
     */
    public function composition()
    {
        return $this->hasOne(Composition::class);
    }

    /**
     * Relation to user; one to many
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
