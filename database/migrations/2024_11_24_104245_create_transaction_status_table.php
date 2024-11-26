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
        Schema::create('transaction_status', function (Blueprint $table) {
            $table->id('transactionStatusID');
            $table->string('transactionStatus');
        });

        DB::table('transaction_status')->insert([
            [
                'transactionStatus' => 'Reserved',
            ],
            [
                'transactionStatus' => 'Wait',
            ],
            [
                'transactionStatus' => 'Done',
            ],
            [
                'transactionStatus' => 'Cancel',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_status');
    }
};
