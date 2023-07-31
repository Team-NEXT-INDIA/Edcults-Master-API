<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelGenerator extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'data' => 'array',
    ];
}
