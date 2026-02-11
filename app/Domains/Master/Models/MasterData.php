<?php

namespace App\Domains\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\MasterDataFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterData extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return MasterDataFactory::new();
    }
}
