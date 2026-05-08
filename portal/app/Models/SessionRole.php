<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Authenticatable;

//class Users extends Model
class SessionRole extends Model
{
    use HasFactory; //, HasApiTokens
    protected $table = 'session_role';

}

