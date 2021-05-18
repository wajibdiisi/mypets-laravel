<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $status_code    =        200;

    public function userSignUp(Request $request) {
        $validator              =        Validator::make($request->all(), [
            "username"          =>          "required|unique:users",
            "name"              =>          "required",
            "email"             =>          "required|email",
            "password"          =>          "required",
            "phone"             =>          "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "Validator Error", "errors" => $validator->errors()],401);
        }
        $validate_username = Validator::make($request->all(),[
            "username" => "required|unique:users",
        ]);
        if($validate_username->fails()) {
            return response()->json(["status" => "failed", "message" => "Username already existed", "errors" => $validator->errors()],401);
        }
        $validate_email = Validator::make($request->all(),[
            "email" => "required|email|unique:users",
        ]);
        if($validate_email->fails()) {
            return response()->json(["status" => "failed", "message" => "Email already existed", "errors" => $validator->errors()],401);
        }

        $username               =       $request->username;
        $name                   =       $request->name;
        $name                   =       explode(" ", $name);
        $first_name             =       $name[0];
        $last_name              =       "";

        if(isset($name[1])) {
            $last_name          =       $name[1];
        }
        if ($request->name == trim($request->name) && strpos($request->name, ' ') !== true) {
            $first_name = $request->name;
        }
        $userDataArray          =       array(
            "username"           =>          $username,
            "first_name"         =>          $first_name,
            "last_name"          =>          $last_name,
            "full_name"          =>          $request->name,
            "email"              =>          $request->email,
            "password"           =>          Hash::make($request->password),
            "phone"              =>          $request->phone,
            "picture"            =>          'default.png',
        );

        $user_status            =           User::where("email", $request->email)->first();

        if(!is_null($user_status)) {
           return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! email already registered"],401);
        }

        $user                   =           User::create($userDataArray);

        if(!is_null($user)) {
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully", "data" => $user],200);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to register"],401);
        }
    }
    public function showProfile(User $user){
        return response()->json($user,200);
    }


    // ------------ [ User Login ] -------------------
    public function userLogin(Request $request) {

        $validator          =       Validator::make($request->all(),
            [
                "email"             =>          "required|email",
                "password"          =>          "required"
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()],401);
        }


        // check if entered email exists in db
        $email_status       =       User::where("email", $request->email)->first();


        // if email exists then we will check password for the same email

        if($email_status) {
            $get_user = User::where("email", $request->email)->first();
            if(Hash::check($request->password, $get_user->password)){
                $password_status =  "Password Correct";
            }else{
                $password_status = null;
            }

            // if password is correct
            if(!is_null($password_status)) {
                $user           =       $this->userDetail($request->email);
                $accessToken = User::where('email',$request->email)->first()->createToken('Personal Token')->accessToken;
                return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $user, "access_token" => $accessToken],200);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Incorrect password."],401);
            }
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Email doesn't exist."],401);
        }
    }

    // ------------------ [ User Detail ] ---------------------
    public function userDetail($username) {
        $user               =       array();
        if($username != "") {
            $user           =       User::where("username", $username)->first();
            return $user;
        }
    }
    public function updateProfile($id,Request $request){
        if($request->user()->id == $id){
        $user = User::where('id',$id)->first();
        $user->update($request->all());
        return response()->json(["status" => $this->status_code, "success" => true]);
        }else{
            return response()->json(["message" => "Unauthorized"]);
        }
    }
}
