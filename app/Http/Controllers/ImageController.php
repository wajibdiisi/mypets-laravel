<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileUpload;
use Storage;
use App\Models\User;

class ImageController extends Controller
{
      public function index(){
        return view('welcome');
      }

      public function getImage($url) {
        $image = Storage::disk('public')->url($url);
        return $image;
    }
}
