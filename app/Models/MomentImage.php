<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MomentImage extends Model
{
    use HasFactory;
    protected $table = 'moment_image';
    protected $fillable = [
        'moment_id',
        'img',
        'caption'
    ];
    protected $primary_key = 'id';

}
