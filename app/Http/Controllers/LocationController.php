<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Indonesia;

class LocationController extends Controller
{
    public function getAllCities(){
        return Indonesia::allCities();
       }
}
