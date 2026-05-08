<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibCate;
use App\Models\LibType;
use App\Http\Controllers\Controller;
class CommicController extends Controller
{

    public function index(){
        $allLibCate = LibCate::all();
        $allLibType = LibType::all();

        return view('v1.views.library.commic.index',['allLibCate' => $allLibCate, 'allLibType' => $allLibType] );
    }
    //add new commic
    public function saveBook(Request $rq){


        if ($_FILES['file']) {
            $targetDir = public_path().'/uploads/';
            $targetFile = $targetDir . basename($_FILES['file']['name']);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
            // Check file size
            if ($_FILES['file']['size'] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
        
            // Allow only certain file formats
            if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
        
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                    echo "The file ". htmlspecialchars(basename($_FILES['file']['name'])). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
        dd($rq);



        $fields = $rq->validate([
            'name_en' => 'required|string',
            'name_vn' => 'required|string',
            'book_type' => 'required|string',
            'book_cate' => 'required|string',
            'book_avartar' => 'required|string',
            'book_author' => 'required',
        ]);
        if($fields['termCond']){
            $user = Users::create([
                'first_name' => $fields['first_name'],
                'last_name' => $fields['last_name'],
                'name'     => $fields['first_name'] . ' '. $fields['last_name'],
                'password' => bcrypt($fields['password']),
                'email' => $fields['email'],
            ]);
            $response = [
                'user' => $user,
            ];
            return response($response, 200);
        }
    }
}
