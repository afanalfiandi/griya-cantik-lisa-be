<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->string('transactionNumber')->primary();
            $table->foreignId('customerID')->constrained('customers', 'customerID')->onDelete('cascade');
            $table->foreignId('slotID')->constrained('slot', 'slotID')->onDelete('cascade');
            $table->foreignId('paymentStatusID')->constrained('payment_status', 'paymentStatusID')->onDelete('cascade');
            $table->foreignId('transactionStatusID')->constrained('transaction_status', 'transactionStatusID')->onDelete('cascade');
            $table->foreignId('paymentMethodID')->constrained('payment_method', 'paymentMethodID')->onDelete('cascade');
            $table->foreignId('specialistID')->constrained('specialist', 'specialistID')->onDelete('cascade');
            $table->string('vaNumber')->nullable();
            $table->dateTime('bookingDate');
            $table->date('dateFor');
            $table->string('notes')->nullable();
            $table->integer('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
