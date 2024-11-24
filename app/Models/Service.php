<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "service";

    protected $fillable = [
        'serviceCategoryID',
        'serviceName',
        'description',
        'price',
        'time',
    ];

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'serviceCategoryID');
    }

    public function serviceDetail()
    {
        return $this->belongsTo(ServiceDetail::class, 'serviceID');
    }
}
