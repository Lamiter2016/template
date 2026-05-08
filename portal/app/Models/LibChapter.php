<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LibChapter extends Model
{
    use HasFactory;
    protected $table = 'lib_chapter';
    protected $fillable = ['id_book','number_chap','order_chap','description','created_at','updated_at','link','status'];

    public static function getChaps($id)
    {
        $chaps = DB::table('lib_chapter')->where([['id_book', '=',$id],['status', '=','Y']])->orderBy('number_chap', 'asc')->get();
        return $chaps;
    }
}
