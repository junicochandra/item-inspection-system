<?php

namespace App\Domains\Sow\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\ScopeIncludedFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScopeIncluded extends Model
{
    use HasFactory;
    protected $fillable = [
            'scope_of_work_id',
            'name',
            'description',
            'is_active'
        ];

    protected static function newFactory()
    {
        return ScopeIncludedFactory::new();
    }

    public function scopeOfWork()
    {
        return $this->belongsTo(ScopeOfWork::class);
    }
}
