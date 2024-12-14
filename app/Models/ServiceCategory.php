<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "service_category";
    protected $primaryKey = 'serviceCategoryID';
    public function services()
    {
        return $this->belongsTo(Service::class, 'serviceCategoryID');
    }
}
