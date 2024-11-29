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
        Schema::create('favourite', function (Blueprint $table) {
            $table->id('favouriteID');
            $table->foreignId('customerID')->constrained('customers', 'customerID')->onDelete('cascade');
            $table->foreignId('serviceID')->constrained('service', 'serviceID')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourite');
    }
};
