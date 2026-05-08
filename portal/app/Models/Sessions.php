<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sessions extends Model
{
    use HasFactory;
    public static function getIdCode($idUser)
    {
        $idCode = DB::table('sessions')->where([['user_id', '=',$idUser],['status', '=','Y']])->first();
        return $idCode;
    }
    public static function getIdSessions($idUser)
    {
        $userId = DB::table('sessions')->where([['user_id', '=',$idUser],['status', '=','Y']])->first();
        return $userId;
    }
}
