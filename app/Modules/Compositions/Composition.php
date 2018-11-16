<?php

namespace Artworch\Modules\Compositions;

use Illuminate\Database\Eloquent\Model;
use Artworch\Modules\User\Account\CompRequest;

class Composition extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'comp_request_id', 'view_status',
        'published_at', 'expire_at'
    ];

    protected $casts = [
        'published_at' => 'date:Y-m-d'
    ];

    /**
     * Relation to composition request; one to one
     *
     * @return void
     */
    public function compRequest()
    {
        return $this->belongsTo(CompRequest::class);
    }
}
