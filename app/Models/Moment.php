<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moment extends Model
{
    use HasFactory;
    protected $table = 'share_moment';
    protected $fillable = [
        'id_user',
        'title',
        'description',
        'animal_name',
        'animal_type',
        'location',
        'gender',
        'picture'
    ];
    protected $primary_key = 'id';
    public function image(){
        return $this->hasMany(MomentImage::class,'moment_id');
    }

}
