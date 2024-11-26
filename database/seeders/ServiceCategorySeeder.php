<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_category')->insert(
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
        );
    }
}
