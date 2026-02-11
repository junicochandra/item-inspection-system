<?php

namespace App\Domains\Sow\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\ScopeOfWorkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScopeOfWork extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'description'];

    protected static function newFactory()
    {
        return ScopeOfWorkFactory::new();
    }

    public function scopeIncludeds()
    {
        return $this->hasMany(ScopeIncluded::class, 'scope_of_work_id');
    }
}
