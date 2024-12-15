<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "transaction_detail";
    protected $primaryKey = 'transactionDetailID';
    protected $fillable = [
        'transactionNumber',
        'serviceID'
    ];

    public function services(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'serviceID');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transactionNumber');
    }
}
