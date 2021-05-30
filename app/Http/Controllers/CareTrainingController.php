<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareTraining;

class CareTrainingController extends Controller
{
    public function showAll(){
        return CareTraining::all();
    }
    public function getSpecificCare($id_care){
        $data = CareTraining::find($id_care);
        if($data){
            return $data;
        }else{
            return response()->json(["status" => "failed", "message" => "Not Found"],404);
        }
    }
}
