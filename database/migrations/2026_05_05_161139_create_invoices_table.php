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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->foreignId('receptionist_id')->constrained('users')->onDelete('cascade');
        $table->decimal('total_amount', 10, 2);
        $table->string('qr_code_path')->nullable();
        $table->enum('status', ['pending', 'paid'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
