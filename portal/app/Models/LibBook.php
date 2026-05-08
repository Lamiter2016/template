<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LibBook extends Model
{
    use HasFactory;
    protected $table = 'lib_book';
    protected $fillable = ['id_cate','id_author','id_type','name','name_vn','views','status','stars','url_img','description','created_at','updated_at'];

    public static function getBook($id)
    {
        $book = DB::table('lib_book')->where([['id', '=',$id],['status', '=','Y']])->first();
        return $book;
    }
}
