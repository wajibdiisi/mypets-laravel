<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Moment;
use App\Models\ApiToken;
use App\Models\Vaccine;
use Auth;

use Illuminate\Support\Facades\Validator;
use Storage;

class VaccineController extends Controller
{
    public function uploadVaccine(Request $request){
        $validator              =        Validator::make($request->all(), [
        "name"          =>          "required",
        "user_id"          => "required",
        "animal_name"   =>          "required",
        "desc"             =>          "required",
        "age"          =>          "required",
        "date"           =>          "required",
        "next_vaccine"  =>          "required",
        "vaccine_type"      => "required",
    ]);

        if($validator->fails()) {
        return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $owner = User::where('id',$request->user_id)->first();

        $array = array(
            "id_user"          => $owner->id,
            "name"          =>          $request->name,
            "description"             =>          $request->desc,
            "animal"   =>          $request->animal_name,
            "gender"    => $request->gender,
            "age"    => $request->age,
            "vaksin_type"          =>          $request->vaccine_type,
            "date"           =>          $request->date,
            "next_vaksin"           =>          $request->next_vaccine,
        );
        $vaccine = Vaccine::create($array);

        $i = 1;
        foreach($request->images as $image){
        $imagePath = Storage::disk('public')->put('Vaccine'. '/' . $owner->id. '/' . $request->title . '/' , $image);
        if($i == 1){
            Vaccine::where('id',$vaccine->id)->update(['picture' =>  $imagePath]);
        }
        }
        return response()->json(["status" => "Success", "message" => "Data Inputed Successfully"], 200);
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
   public function getVaccine($username){
        $user = User::where('username',$username)->first();
        $vaccine = Vaccine::where('id_user',$user->id)->get();
        foreach($vaccine as &$adopt){
            $adopt->owner  = $user->full_name;
            $adopt->owner_avatar = $user->picture;

        };
        return $vaccine;
   }
   public function getMomentByID($id){
       $moment = Moment::where('id',$id)->first();
        return $moment;
   }
   public function getMomentByBreeds($breeds_type){
    $moment = Moment::where('animal_type',$breeds_type)->get();
    foreach($moment as &$adopt){
        $user = User::where('id',$adopt->id_user)->first();
        $adopt->owner  = $user->full_name;
        $adopt->owner_avatar = $user->picture;

    };
    return $moment;
}
public function patchMoment(Request $request,$id_user,$id_moment){
    $validator              =        Validator::make($request->all(), [
        "title"          =>          "required",
        "user_id"          => "required",
        "animal_name"   =>          "required",
        "desc"             =>          "required",
        "gender"            => "required",
        "type"          =>          "required",
        "loc"           =>          "required",
    ]);

        if($validator->fails()) {
        return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()],422);
    }
    $moment = Moment::where('id',$id_moment)->first();
    if($id_user == $moment->id_user){
        $array = array(
            "title"          =>          $request->title,
            "description"             =>          $request->desc,
            "animal_name"   =>          $request->animal_name,
            "gender"    => $request->gender,
            "animal_type"          =>          $request->type,
            "location"           =>          $request->loc
        );
        $moment->update($array);
        return response()->json($array,200);
    }
}
public function deleteVaccine(Request $request,$id_user,$id_vaccine){

    $auth_header = explode(' ', $request->bearerToken());
    $token = $auth_header[0];
    $token_parts = explode('.', $token);
    $token_header = $token_parts[1];
    $token_header_json = base64_decode($token_header);
    $token_header_array = json_decode($token_header_json, true);
    $user_token = $token_header_array['jti'];
    $moment = Vaccine::where('id',$id_vaccine)->first();
    $user = ApiToken::where('id', $user_token)->first();
    if($moment->id_user == $user->user_id)
    {
        $id = $moment->id;
        $moment->delete();
        response()->json(["id" => $id, "status" => "success", "message" => "Data Deleted Successfully"],200);
        return $user;
    }else{
        return response()->json(["status" => "failed", "message" => "Unauthorized"],422);
    }
    ;
}
}
