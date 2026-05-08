<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

//class Users extends Model
class Users extends Authenticatable
{
    use HasFactory, Notifiable; //, HasApiTokens
    protected $fillable = ['first_name', 'facebook_id', 'last_name', 'name', 'password', 'email', 'id_code', 'status', 'role_id'];
    public static function getUser($email)
    {
        $user = DB::table('users')->where('email', $email)->first();
        return $user;
    }
    public static function getRoleId($id){
        $roleId = DB::table('user_role')->where('user_id', $id)->first();
        return $roleId->role_id;
    }
}

