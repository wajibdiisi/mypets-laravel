<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Moment;
use App\Models\User;
class Animal extends Model
{
    use HasFactory;
    protected $table = 'animal';
    public function moments(){
        return $this->hasMany(Moment::class, 'animal_type','slug');
    }
    public function user(){
        return $this->belongsToMany(User::class,'animal_user');
    }

}
