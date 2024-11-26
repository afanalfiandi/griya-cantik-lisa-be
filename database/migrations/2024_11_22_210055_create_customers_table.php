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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customerID');
            $table->string('username');
            $table->string('password');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('img');
        });

        DB::table('customers')->insert([
            [
                'username' => 'stefany',
                'password' => bcrypt(123456),
                'firstName' => 'Stefany',
                'lastName' => 'Anggita Prameswari',
                'img' => 'default.png',
            ],
            [
                'username' => 'dev1',
                'password' => bcrypt(123456),
                'firstName' => 'Dev',
                'lastName' => '1',
                'img' => 'default.png',
            ],
            [
                'username' => 'dev2',
                'password' => bcrypt(123456),
                'firstName' => 'Dev',
                'lastName' => '2',
                'img' => 'default.png',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
