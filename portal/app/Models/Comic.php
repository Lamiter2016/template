<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comic extends Model
{
    use HasFactory;
    protected $table = 'comic';
    protected $fillable = ['name', 'chapNum', 'link_down', 'link_store', 'status_down'];
    public static function saveComic($name = 'undefined', $chapNum = '', $link_down = '', $link_store = '', $status_down = 'pending'){
        $comic =  new Comic();
        $comic->name = $name;
        $comic->chapNum = $chapNum;
        $comic->link_down = $link_down;
        $comic->link_store = $link_store;
        $comic->status_down = $status_down;
        $comic->save();
    }
}
