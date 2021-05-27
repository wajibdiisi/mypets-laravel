<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function showArticle($category){
        return Article::where('category',$category)->get();
    }
    public function specificArticle($id){
        return Article::find($id);
    }
}
