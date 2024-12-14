<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "service";
    protected $primaryKey = 'serviceID';
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

    public function serviceDetail(): HasMany
    {
        return $this->hasMany(ServiceDetail::class, 'serviceID');
    }

    protected static function booted()
    {
        static::saved(function (Service $service) {
            $uploadedFiles = request()->file('img');

            if ($uploadedFiles) {
                foreach ($uploadedFiles as $file) {
                    $path = $file->store('public');

                    ServiceDetail::create([
                        'service_id' => $service->id,
                        'img' => $path,
                    ]);
                }
            }
        });
    }
}
