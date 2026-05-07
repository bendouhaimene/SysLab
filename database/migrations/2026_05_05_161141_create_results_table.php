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
    Schema::create('results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
        $table->foreignId('test_id')->constrained()->onDelete('cascade');
        $table->foreignId('biologist_id')->constrained('users')->onDelete('cascade');
        $table->string('value')->nullable();
        $table->enum('status', ['pending', 'submitted', 'validated', 'rejected'])->default('pending');
        $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
        $table->text('doctor_note')->nullable();
        $table->timestamp('submitted_at')->nullable();
        $table->timestamp('validated_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
