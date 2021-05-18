<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Moment;
use App\Models\MomentImage;

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
   public function getMoment($username){
        $user = User::where('username',$username)->first();
        $moment = Moment::where('id_user',$user->id)->get();
        foreach($moment as &$adopt){
            $adopt->owner  = $user->full_name;
            $adopt->owner_avatar = $user->picture;

        };
        return $moment;
   }
}
