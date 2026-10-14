<?php

namespace App\Entities;

use App\Entities\Entity;

class Settings extends Entity
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $datamap = [
        'key'   => 'settings_key',
        'value' => 'settings_value',
    ];
}
