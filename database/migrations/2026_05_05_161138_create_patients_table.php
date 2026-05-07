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
    Schema::create('patients', function (Blueprint $table) {
        $table->id();
        $table->string('national_id', 18)->unique();
        $table->string('first_name');
        $table->string('last_name');
        $table->date('date_of_birth')->nullable();
        $table->enum('gender', ['male', 'female'])->nullable();
        $table->string('phone')->nullable();
        $table->string('username')->unique();
        $table->string('password');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
