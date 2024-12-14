<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "transaction_status";
    protected $primaryKey = 'transactionStatusID';
    protected $fillable = [
        'transactionStatus',
    ];
}
