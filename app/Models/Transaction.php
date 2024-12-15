<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "transaction";
    protected $primaryKey = 'transactionNumber';
    public $incrementing = false;  // Nonaktifkan auto increment
    protected $keyType = 'string';
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

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customerID');
    }
    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slotID');
    }
    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'paymentStatusID');
    }
    public function transactionStatus()
    {
        return $this->belongsTo(TransactionStatus::class, 'transactionStatusID');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'paymentMethodID');
    }
    public function specialist()
    {
        return $this->belongsTo(Specialist::class, 'specialistID');
    }
    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'transactionNumber');
    }
}
