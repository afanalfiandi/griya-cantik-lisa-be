<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "transaction";

    protected $fillable = [
        'transactionNumber',
        'customerID',
        'slotID',
        'paymentStatusID',
        'transactionStatusID',
        'paymentMethodID',
        'specialistID',
        'vaNumber',
        'bookingDate',
        'dateFor',
        'notes',
        'subtotal',
    ];
}
