<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LibContent extends Model
{
    use HasFactory;
    protected $table = 'lib_content';
    protected $fillable = ['id_chap','order','content','created_at','updated_at'];

    public static function getContent($id)
    {
        $content = DB::table('lib_content')->where([['id_chap', '=',$id]])->first();
        return $content;
    }
}
