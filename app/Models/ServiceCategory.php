<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "service_category";

    public function services()
    {
        return $this->hasMany(Service::class, 'serviceCategoryID');
    }
}
