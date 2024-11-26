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
        Schema::create('specialist', function (Blueprint $table) {
            $table->id('specialistID');
            $table->string('specialistName');
            $table->string('img');
        });

        DB::table('specialist')->insert([
            [
                'specialistID' => 1,
                'specialistName' => 'Guteng',
                'img' => 'default.png',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialist');
    }
};
