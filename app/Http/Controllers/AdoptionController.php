<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption;
use Illuminate\Support\Facades\Validator;

class AdoptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator              =        Validator::make($request->all(), [
            "name"          =>          "required",
            "owner"              =>          "required",
            "type"             =>          "required",
            "subtype"          =>          "required",
            "picture"           =>          "required"
        ]);
        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $adoptionDataArray = array(
            "name"          =>          $request->name,
            "owner"              =>     $request->owner,
            "type"             =>       $request->type,
            "subtype"          =>       $request->subtype,
            "picture"           =>      $request->picture
        );
        Adoption::create($adoptionDataArray);
        return response()->json(["success" => true, "message" => "Success"]);
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
    public function show($type, $slug = NULL)
    {
        if($type && $slug === null){
            return Adoption::where('type',$type)->get();
        }elseif($type && $slug){
            return Adoption::where('type',$type)->where('subtype',$slug)->get();
        }
    }
    public function showAll()
    {
        return Adoption::all();

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
