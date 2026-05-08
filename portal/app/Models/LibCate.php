<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LibCate extends Model
{
    use HasFactory;
    protected $table = 'lib_cate';
    protected $fillable = ['name_vn', 'name_en', 'id_type', 'status', 'description'];

    public static function getAllCate($idUser)
    {
        $userId = DB::table('lib_cate')->where([['user_id', '=',$idUser],['status', '=','Y']])->first();
        return $userId;
    }
}
