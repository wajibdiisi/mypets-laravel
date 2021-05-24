<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


}
