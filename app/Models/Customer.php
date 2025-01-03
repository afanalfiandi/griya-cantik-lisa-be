<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'customers';
    protected $fillable = ['firstName', 'lastName', 'username', 'password', 'img'];
    protected $primaryKey = 'customerID';

    protected $guarded = [];

    protected $hidden = ['password'];

    public $timestamps = false;

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
