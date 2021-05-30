<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Animaltype;
use App\Models\DogDetail;
use App\Models\CatDetail;

class animalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Animal::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function addLike($slug,$user_id){
        $animal = Animal::where('slug',$slug)->first();
        if($animal->user()->where('user_id',$user_id)->exists())
        $animal->user()->detach($user_id);
        else{
            $animal->user()->attach($user_id);
        }
    }
    public function showAll(){
        return Animal::all();

    }

    public function getAnimalLikeUser($slug,$user_id){
        $animal = Animal::where('slug',$slug)->first();
        if($animal->user()->where('user_id',$user_id)->exists()){
            return response()->json(["message" => true]);
        }else{
            return response()->json(["message" => false]);
        }
    }

    public function showAnimal($type){
        return Animal::with('moments','user')->where('type',$type)->get();
    }
    public function showAnimalSpecies($type,$slug){
        return Animal::with('moments','user')->where('type',$type)->where('slug',$slug)->get();
    }
    public function showAnimalDetail($type,$slug){
        if($type == "dog"){
            $dog = DogDetail::where('slug',$slug)->first();
            if($dog){
                return $dog;
            }else {
                return response()->json(["message" => "Page Not Found"],404);
            }
        }else if ($type == "cat"){
            $animal =  CatDetail::where('slug',$slug)->first();
            if($animal){
                return $animal;
            }else {
                return response()->json(["message" => "Page Not Found"],404);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
