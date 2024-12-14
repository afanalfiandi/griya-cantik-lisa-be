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
        Schema::create('admin', function (Blueprint $table) {
            $table->id('adminID');
            $table->string('email')->default('default@example.com');
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
        });

        DB::table('admin')->insert([
            [
                'email' => 'admin@gmail.com',
                'password' => bcrypt(123456),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
