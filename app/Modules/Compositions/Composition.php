<?php

namespace Artworch;

use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{

    protected $fillable = [
        'id', 'freeze_picture', 'preview_picture',
        'custom_price', 'title'
    ];

}
