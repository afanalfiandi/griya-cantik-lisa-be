<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "specialist";

    protected $fillable = [
        'specialistName',
        'img'
    ];
}
