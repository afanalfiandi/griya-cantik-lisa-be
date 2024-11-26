<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_status', function (Blueprint $table) {
            $table->id('paymentStatusID');
            $table->string('paymentStatusName');
            $table->string('description');
        });

        DB::table('payment_status')->insert([
            [
                'paymentStatusName' => 'settlement',
                'description' => 'Transaction is successfully paid, customer has completed the transaction.',
            ],
            [
                'paymentStatusName' => 'pending',
                'description' => 'Transaction is created successfully but it is not completed by the customer.',
            ],
            [
                'paymentStatusName' => 'expire',
                'description' => 'Transaction is failed as the payment is not done by customer within the given time period.',
            ],
            [
                'paymentStatusName' => 'cancel',
                'description' => 'Transaction is cancelled by you.',
            ],
            [
                'paymentStatusName' => 'deny',
                'description' => 'Transaction is rejected by the bank.',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_status');
    }
};
