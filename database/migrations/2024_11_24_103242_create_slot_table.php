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
        Schema::create('slot', function (Blueprint $table) {
            $table->id('slotID');
            $table->string('time');
            $table->integer('slot');
        });

        DB::table('slot')->insert([
            [
                'time' => '09:00',
                'slot' => '10',
            ],
            [
                'time' => '10:00',
                'slot' => '10',
            ],
            [
                'time' => '11:00',
                'slot' => '10',
            ],
            [
                'time' => '13:00',
                'slot' => '10',
            ],
            [
                'time' => '14:00',
                'slot' => '10',
            ],
            [
                'time' => '15:00',
                'slot' => '10',
            ],
            [
                'time' => '16:00',
                'slot' => '10',
            ],
            [
                'time' => '17:00',
                'slot' => '10',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot');
    }
};
