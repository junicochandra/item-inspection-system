<?php

namespace App\Domains\Lot\Models;

use Database\Factories\LotFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lot extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return LotFactory::new();
    }
}
