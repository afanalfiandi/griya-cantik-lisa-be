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
        Schema::create('service_category', function (Blueprint $table) {
            $table->id('serviceCategoryId');
            $table->string('serviceCategoryName');
            $table->string('img');
        });

        DB::table('service_category')->insert([
            [
                'serviceCategoryName' => 'Hair Care',
                'img'                 => 'hair_care.png',
            ],
            [
                'serviceCategoryName' => 'Nails',
                'img'                 => 'nails.png',
            ],
            [
                'serviceCategoryName' => 'Face Care',
                'img'                 => 'face_care.png',
            ],
            [
                'serviceCategoryName' => 'Hand and Foot Care',
                'img'                 => 'hand_and_foot_care.png',
            ],
            [
                'serviceCategoryName' => 'Spa',
                'img'                 => 'spa.png',
            ],
            [
                'serviceCategoryName' => 'Waxing',
                'img'                 => 'waxing.png',
            ],
            [
                'serviceCategoryName' => 'Paket Treatment',
                'img'                 => 'paket_treatment.png',
            ],
            [
                'serviceCategoryName' => 'Body Care',
                'img'                 => 'body_care.png',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_category');
    }
};
