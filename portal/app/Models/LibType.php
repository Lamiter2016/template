<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibType extends Model
{
    use HasFactory;
    protected $table = 'lib_type';
    protected $fillable = ['name_vn', 'name_en', 'status', 'description'];
}
