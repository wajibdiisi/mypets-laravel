<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;
    protected $table = 'vaksin';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'name',
        'description',
        'animal',
        'age',
        'date',
        'next_vaksin',
        'vaksin_type',
        'picture',
        'gender'
    ];
    protected $primary_key = 'id';
}
