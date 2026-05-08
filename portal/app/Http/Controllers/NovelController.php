<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibBook;
use App\Models\LibChapter;
use App\Models\LibContent;


class NovelController extends Controller
{
    //
    public function detail(Request $request){
        $book = LibBook::getBook(1);
        $chaps = LibChapter::getChaps($book->id);
        $contentList = array();
        // foreach ($chaps as $key => $item) {
        //     $content = LibContent::getContent($item->id);
        //     $contentArr = array();
        //     $contentArr["number_chap"] = $item->number_chap;
        //     $contentArr["content"] = $content->content;
        //     $contentArr["description"] = $item->description;
        //     array_push($contentList, $contentArr);
        // };
        return view('v1.views.library.novel.detail');
    }
}
