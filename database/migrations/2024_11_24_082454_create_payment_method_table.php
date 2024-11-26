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
            $table->string('img');
        });

        DB::table('payment_method')->insert([
            [
                'paymentMethodName' => 'BCA Virtual Account',
                'img' => 'bca.png',
            ],
            [
                'paymentMethodName' => 'BNI Virtual Account',
                'img' => 'bni.png',
            ],
            [
                'paymentMethodName' => 'BRI Virtual Account',
                'img' => 'bri.png',
            ],
            [
                'paymentMethodName' => 'Mandiri Bill Payment',
                'img' => 'mandiri.png',
            ],
            [
                'paymentMethodName' => 'Permata Virtual Account',
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
