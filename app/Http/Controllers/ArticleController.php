<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function showArticle($category){
        return Article::where('category',$category)->get();
    }
    public function specificArticle($id){
        $article = Article::find($id);
        if($article){

            return $article;
        }else{
            return response()->json(["message" => "Page Not Found"],404);
        }
    }
}
