<?php

namespace Artworch\Modules\Compositions;

use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{

    protected $fillable = [
        'id', 'freeze_picture', 'preview_picture',
        'custom_price', 'title'
    ];

    protected $casts = [
        'published_date' => 'date:Y-m-d'
    ];
}
