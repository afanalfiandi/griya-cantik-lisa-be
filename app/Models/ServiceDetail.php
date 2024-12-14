<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceDetail extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "service_detail";
    protected $primaryKey = 'serviceDetailID';
    protected $fillable = [
        'serviceID',
        'img',
    ];

    public function services(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'serviceID');
    }
}
