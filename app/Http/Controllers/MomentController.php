<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Moment;
use App\Models\ApiToken;
use App\Models\MomentImage;
use Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use Storage;

class MomentController extends Controller
{
    public function uploadMoment(Request $request){
        $validator              =        Validator::make($request->all(), [
        "title"          =>          "required",
        "user_id"          => "required",
        "animal_name"   =>          "required",
        "desc"             =>          "required",
        "type"          =>          "required",
        "loc"           =>          "required",
        "date"              =>      "required",
        "images"         =>          "required"
    ]);
        if($validator->fails()) {
        return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()],422);
        }
        $owner = User::where('id',$request->user_id)->first();
        $array = array(
            "id_user"          => $owner->id,
            "title"          =>          $request->title,
            "description"             =>          $request->desc,
            "animal_name"   =>          $request->animal_name,
            "gender"    => $request->gender,
            "animal_type"          =>          $request->type,
            "date"                  => $request->date,
            "location"           =>          $request->loc
        );
        $moment = Moment::create($array);
        $i = 1;
        foreach($request->images as $image){
        $imagePath = Storage::disk('s3')->put('Moment'. '/' . $owner->id. '/' . $request->title , $image);
        if($i == 1){
            $moment->update(['picture' =>  $imagePath]);
        }
        MomentImage::create([
            'moment_id' => $moment->id,
            'img' => $imagePath,
            'caption' => 'tes'
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
   public function getSpecificMoment($id){
            $moment = Moment::with('user','image')->where('id',$id)->first();
            if($moment){
                $moment->time = Carbon::parse($moment->created_at)->format('l, d-M-Y H:i:s');
                $user = User::where('id',$moment->id_user)->first();
                $moment->email = $user->email;
                $moment->phone = $user->phone;
                $moment->picture = $user->picture;
                $moment->full_name = $user->full_name;
                $moment->username = $user->username;
                return $moment;
    }else {
        return response()->json(["message" => "Page Not Found"],404);
    }
   }
   public function getMoment($username){
        $user = User::where('username',$username)->first();
        $moment = Moment::where('id_user',$user->id)->orderByDesc('created_at')->get();
        foreach($moment as &$adopt){
            $adopt->owner  = $user->full_name;
            $adopt->owner_avatar = $user->picture;
            $adopt->publish_time = $adopt->created_at->diffForHumans();

        };
        return $moment;
   }
   public function getMomentByID(Request $request,$id){
       $moment = Moment::with('image','user')->where('id',$id)->orderByDesc('created_at')->first();
        if($moment){
            $auth_header = explode(' ', $request->bearerToken());
            $token = $auth_header[0];
            $token_parts = explode('.', $token);
            $token_header = $token_parts[1];
            $token_header_json = base64_decode($token_header);
            $token_header_array = json_decode($token_header_json, true);
            $user_token = $token_header_array['jti'];
            $user = ApiToken::where('id', $user_token)->first();
            if($moment->id_user == $user->user_id){
                return $moment;
            }else {
                return response()->json(['message' => "Unauthorized Access"],422);
            }
        }else{
            return response()->json(["message" => "Page Not Found"],404);
        }
    }
   public function getMomentByBreeds($breeds_type){
    $moment = Moment::where('animal_type',$breeds_type)->orderByDesc('created_at')->get();
    foreach($moment as &$adopt){
        $user = User::where('id',$adopt->id_user)->first();
        $adopt->owner  = $user->full_name;
        $adopt->owner_avatar = $user->picture;
        $adopt->publish_time = $adopt->created_at->diffForHumans();

    };
    return $moment;
}
public function patchMoment(Request $request,$id_user,$id_moment){
        $auth_header = explode(' ', $request->bearerToken());
        $token = $auth_header[0];
        $token_parts = explode('.', $token);
        $token_header = $token_parts[1];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        $user_token = $token_header_array['jti'];
        $moment = Moment::where('id',$id_moment)->first();
        $user = ApiToken::where('id', $user_token)->first();
        if($moment->id_user == $user->user_id){
    $validator              =        Validator::make($request->all(), [
        "title"          =>          "required",
        "user_id"          => "required",
        "animal_name"   =>          "required",
        "desc"             =>          "required",
        "gender"            => "required",
        "type"          =>          "required",
        "loc"           =>          "required",
        "date"        =>          "required"
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
            "location"           =>          $request->loc,
            "date"          =>      $request->date
        );
        $moment->update($array);
        return response()->json($array,200);
    }
    }else {
        return response()->json(['message' => "Unauthorized Access"],422);
    }
}
    public function deleteMoment(Request $request,$id_user,$id_moment){

        $auth_header = explode(' ', $request->bearerToken());
        $token = $auth_header[0];
        $token_parts = explode('.', $token);
        $token_header = $token_parts[1];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        $user_token = $token_header_array['jti'];
        $moment = Moment::where('id',$id_moment)->first();
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
