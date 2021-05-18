<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\AdoptionImage;
use App\Models\User;
use App\Models\Moment;
use App\Models\MomentImage;
use Storage;
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
    public function adoptionImage($adoption_id,$count){
        if($count == 1){
        return AdoptionImage::where('adoption_id',$adoption_id)->first();

    }
    }
    public function uploadMoment(Request $request){
        $validator              =        Validator::make($request->all(), [
        "title"          =>          "required",
        "user_id"          => "required",
        "animal_name"   =>          "required",
        "desc"             =>          "required",
        "type"          =>          "required",
        "loc"           =>          "required",
    ]);

        if($validator->fails()) {
        return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $owner = User::where('id',$request->user_id)->first();
        $array = array(
            "id_user"          => $owner->id,
            "title"          =>          $request->title,
            "description"             =>          $request->desc,
            "animal_name"   =>          $request->animal_name,
            "animal_type"          =>          $request->type,
            "location"           =>          $request->loc
        );
        $moment = Moment::create($array);
        $i = 1;
        foreach($request->images as $image){
        $imagePath = Storage::disk('public')->put('Moment'. '/' . $owner->id. '/' . $request->title . '/' , $image);
        if($i == 1){
            Moment::where('id_moment',$moment->id)->update(['picture' =>  $imagePath]);
        }
        MomentImage::create([
            'moment_id' => $moment->id,
            'img' => $imagePath,
            'caption' => 'tes'
            ]);
        }
        /*$fileUpload = new FileUpload;

        if($request->file()) {
            $file_name = time().'_'.$request->images->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');

            $fileUpload->name = time().'_'.$request->images->getClientOriginalName();
            $fileUpload->path = '/../../public/image' . $file_path;
            $fileUpload->save();

            return response()->json(['success'=>'File uploaded successfully.']);
        }
        */
   }


    public function uploadAdoption(Request $request){
            $validator              =        Validator::make($request->all(), [
            "name"          =>          "required",
            "animal_name"   =>          "required",
            "desc"             =>          "required",
            "type"          =>          "required",
            "gender"           =>          "required",
            "color"           =>          "required",
            "loc"           =>          "required",
            "body"           =>          "required",
            "health"           =>          "required",
            "images"          => "required",
        ]);

            if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
            }
            $owner = User::where('id',$request->user_id)->first();
            $adoptionDataArray = array(
                "name"          =>          $request->name,
                "description"             =>          $request->desc,
                "animal_name"          =>          "Dog",
                "animal_type"          =>          $request->type,
                "gender"           =>          $request->gender,
                "color"           =>          $request->color,
                "location"           =>         $request->loc,
                "body"           =>          $request->body,
                "age"           => $request->age,
                "health"           =>          $request->health,
                "owner"             => $owner->full_name,
                "id_user"       => $owner->id,
            );
            $adopt = Adoption::create($adoptionDataArray);
            $i = 1;
            foreach($request->images as $image){
            $fileName = $i .  '.' . $image->getClientOriginalExtension();

            $imagePath = Storage::disk('public')->put('Adoption'. '/' . $owner->id. '/' . '/'. $request->name . '/' , $image);
            if($i == 1){
                $adopt->update(['picture' =>  $imagePath]);
            }
            AdoptionImage::create([
                'adoption_id' => $adopt->id,
                'caption' =>'test',
                'img' => $imagePath,
                ]);
            $i++;

        }
            /*$fileUpload = new FileUpload;

            if($request->file()) {
                $file_name = time().'_'.$request->images->getClientOriginalName();
                $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');

                $fileUpload->name = time().'_'.$request->images->getClientOriginalName();
                $fileUpload->path = '/../../public/image' . $file_path;
                $fileUpload->save();

                return response()->json(['success'=>'File uploaded successfully.']);
            }
            */
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
    public function getSpecificAdoption($adoption_id){
        $adopt = Adoption::where('id',$adoption_id)->first();
        $adopt->user = User::where('id',$adopt->id_user)->first();
        return $adopt;
    }
    public function show($type, $slug = NULL)
    {
        $adoption = new Object();
        if($type && $slug === null){
            $adoption = Adoption::where('type',$type)->get();

        }elseif($type && $slug){
            $adoption = Adoption::where('type',$type)->where('subtype',$slug)->get();

        };
        $counter = 0;
        foreach($adoption as &$pic){
            $pic->picture = 'http://localhost:8000/storage/' . $pic->picture;

        };
        return $adoption;
    }
    public function showAll()
    {
        $adoption = Adoption::all();
        foreach($adoption as &$adopt){
            $user = User::where('id',$adopt->id_user)->first();
            $adopt->owner  = $user->full_name;
            $adopt->owner_avatar = $user->picture;
          //  $adopt->picture = 'http://localhost:8000/storage/' . $adopt->picture;

        };
        return $adoption;

    }
    public function userAdoption($username){
        $user = User::where('username',$username)->first();
        $adoption = Adoption::where('id_user',$user->id)->get();
        foreach($adoption as &$adopt){
            $adopt->owner  = $user->full_name;
            $adopt->owner_avatar = $user->picture;
          //  $adopt->picture = 'http://localhost:8000/storage/' . $adopt->picture;

        };
        return $adoption;
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
