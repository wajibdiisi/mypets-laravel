<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionImage extends Model
{
    use HasFactory;
    protected $table = 'adoption_image';
    protected $fillable = [
        'adoption_id',
        'img',
        'caption'
    ];
    protected $primary_key = 'id';

}
