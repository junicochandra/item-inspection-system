<?php

namespace App\Domains\Item\Models;

use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return ItemFactory::new();
    }
}
