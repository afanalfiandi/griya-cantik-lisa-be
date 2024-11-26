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
        Schema::create('payment_method', function (Blueprint $table) {
            $table->id('paymentMethodID');
            $table->string('paymentMethodName');
            $table->string('value');
            $table->string('payment_type');
            $table->string('img');
        });

        DB::table('payment_method')->insert([
            [
                'paymentMethodName' => 'BCA Virtual Account',
                'value' => 'bca',
                'payment_type' => 'bank_transfer',
                'img' => 'bca.png',
            ],
            [
                'paymentMethodName' => 'BNI Virtual Account',
                'value' => 'bni',
                'payment_type' => 'bank_transfer',
                'img' => 'bni.png',
            ],
            [
                'paymentMethodName' => 'BRI Virtual Account',
                'value' => 'bri',
                'payment_type' => 'bank_transfer',
                'img' => 'bri.png',
            ],
            [
                'paymentMethodName' => 'Mandiri Bill Payment',
                'value' => 'mandiri',
                'payment_type' => 'echannel',
                'img' => 'mandiri.png',
            ],
            [
                'paymentMethodName' => 'Permata Virtual Account',
                'value' => 'permata',
                'payment_type' => 'bank_transfer',
                'img' => 'permata.png',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};
