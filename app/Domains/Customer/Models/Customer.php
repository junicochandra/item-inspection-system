<?php

namespace App\Domains\Customer\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return CustomerFactory::new();
    }
}
