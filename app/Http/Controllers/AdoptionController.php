<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\AdoptionImage;
use App\Models\ApiToken;
use App\Models\User;
use App\Models\Moment;
use App\Models\MomentImage;
use Storage;
use Indonesia;
use Carbon\Carbon;
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
            return response()->json($validator->errors(),422);
            }
            $owner = User::where('id',$request->user_id)->first();
            $adoptionDataArray = array(
                "name"          =>          $request->name,
                "description"             =>          $request->desc,
                "animal_name"          =>         $request->animal_name,
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

            $imagePath = Storage::disk('s3')->put('Adoption'. '/' . $owner->id. '/' . $request->name , $image);
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
    public function patchAdoption(Request $request,$id_user,$id_adoption){

        $auth_header = explode(' ', $request->bearerToken());
        $token = $auth_header[0];
        $token_parts = explode('.', $token);
        $token_header = $token_parts[1];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        $user_token = $token_header_array['jti'];
        $adoption = Adoption::where('id',$id_adoption)->first();
        $user = ApiToken::where('id', $user_token)->first();
        if($adoption->id_user == $user->user_id){
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
    ]);

        if($validator->fails()) {
        return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()],422);
    }
    $adoption = Adoption::where('id',$id_adoption)->first();
    $owner = User::where('id',$request->user_id)->first();
    if($id_user == $adoption->id_user){
        $array = array(
            "name"          =>          $request->name,
                "description"             =>          $request->desc,
                "animal_name"          =>          $request->animal_name,
                "animal_type"          =>          $request->type,
                "gender"           =>          $request->gender,
                "color"           =>          $request->color,
                "location"           =>         $request->loc,
                "body"           =>          $request->body,
                "age"           => $request->age,
                "health"           =>          $request->health,
                "owner"             => $owner->full_name,
                "id_user"       => $owner->id,
                "adoption_status"   => $request->adoption_status,
        );
        $adoption->update($array);
        return response()->json($array,200);
    }
    }else {
        return response()->json(['message' => "Unauthorized Access"],422);
    }
    }
    public function getAdoptionImage($id){
        $images = AdoptionImage::where('adoption_id',$id)->get();
        return $images;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSpecificAdoption($adoption_id){
        $adopt = Adoption::with('interest','user')->where('id',$adoption_id)->first();
        $adopt->time = Carbon::parse($adopt->created_at)->format('l, d-M-Y H:i:s');
        $user = User::where('id',$adopt->id_user)->first();
        $adopt->email = $user->email;
        $adopt->phone = $user->phone;
        $adopt->picture = $user->picture;
        $adopt->full_name = $user->full_name;
        $adopt->username = $user->username;
        return $adopt;
    }
    public function show($type, $slug = NULL)
    {
        $adoption = new Object();
        if($type && $slug === null){
            $adoption = Adoption::with('image')->where('type',$type)->get();

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
        $adoption = Adoption::with('user','image')->where('adoption_status',0)->orderByDesc('created_at')->get();
        foreach($adoption as &$adopt){
            $user = User::where('id',$adopt->id_user)->first();

            $adopt->upload_time = $adopt->created_at->diffForHumans();
          //  $adopt->picture = 'http://localhost:8000/storage/' . $adopt->picture;

        };
        return $adoption;

    }
    public function latestAdoption($count)
    {
        $adoption = Adoption::with('user','image')->orderByDesc('created_at')->take($count)->get();
        foreach($adoption as &$adopt){
            $user = User::where('id',$adopt->id_user)->first();

            $adopt->upload_time = $adopt->created_at->diffForHumans();
          //  $adopt->picture = 'http://localhost:8000/storage/' . $adopt->picture;

        };
        return $adoption;

    }
    public function userAdoption($username){
        $user = User::where('username',$username)->first();
        $adoption = Adoption::with('image')->where('id_user',$user->id)->get();
        foreach($adoption as &$adopt){
            $adopt->owner  = $user->full_name;
            $adopt->owner_avatar = $user->picture;
          //  $adopt->picture = 'http://localhost:8000/storage/' . $adopt->picture;

        };
        return $adoption;
    }
    public function deleteAdoption(Request $request,$id_user,$id_adoption){

        $auth_header = explode(' ', $request->bearerToken());
        $token = $auth_header[0];
        $token_parts = explode('.', $token);
        $token_header = $token_parts[1];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        $user_token = $token_header_array['jti'];
        $adoption = Adoption::where('id',$id_adoption)->first();
        $user = ApiToken::where('id', $user_token)->first();
        if($adoption->id_user == $user->user_id)
        {
            $id = $adoption->id;
            $adoption->delete();
            response()->json(["id" => $id, "status" => "success", "message" => "Data Deleted Successfully"],200);
            return $user;
        }else{
            return response()->json(["status" => "failed", "message" => "Unauthorized"],422);
        }
        ;
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
    public function addInterest($id_adoption,$user_id){
        $adopt = Adoption::find($id_adoption);
        if($adopt->interest()->where('user_id',$user_id)->exists()){
        $adopt->interest()->detach($user_id);
        }else{
            $adopt->interest()->attach($user_id);
        }
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
