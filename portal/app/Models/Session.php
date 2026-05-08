<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Session extends Model
{
    use HasFactory;
    protected $fillable = ['session_id', 'user_id', 'name', 'token', 'status', 'expiration'];
    protected $table = 'session';
    public static function getToken($idUser)
    {
        $token = DB::table('session')->where([['user_id', '=',$idUser],['status', '=','Y']])->first();
        return $token;
    }
    public static function getIdSessions($idUser)
    {
        $userId = DB::table('session')->where([['user_id', '=',$idUser],['status', '=','Y']])->first();
        return $userId->id;
    }
}




