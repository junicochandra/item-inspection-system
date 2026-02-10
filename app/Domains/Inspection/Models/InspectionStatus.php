<?php

namespace App\Domains\Inspection\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionStatus extends Model
{
    protected $fillable = ['code', 'label'];
}
