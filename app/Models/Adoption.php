<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoption extends Model
{
    use HasFactory;
    protected $table = 'adoption';
    protected $fillable = [
        'name',
        'owner',
        'type',
        'subtype'
    ];
    protected $primaryKey = 'id';

}
