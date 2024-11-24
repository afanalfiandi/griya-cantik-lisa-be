<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    use HasFactory;

    protected $table = "service_detail";

    public function services()
    {
        return $this->hasMany(Service::class, 'serviceID');
    }
}
