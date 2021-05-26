<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdoptionImage;
use App\Models\User;
class Adoption extends Model
{
    use HasFactory;
    protected $table = 'adoption';
    protected $fillable = [
        'animal_name',
        'animal_type',
        'name',
        'description',
        'age',
        'color',
        'owner',
        'gender',
        'body',
        'health',
        'location',
        'id_user',
        'picture'
    ];
    public function image(){
        return $this->hasMany(AdoptionImage::class,'adoption_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'id_user','id');
    }



}
