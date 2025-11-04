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
        Schema::create('committee_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('committee_id')->constrained('committees')->onDelete('cascade');
            $table->enum('vote', ['approved', 'rejected'])->default('approved');
            $table->decimal('amount_approved', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Ensure one vote per committee member per application
            $table->unique(['application_id', 'committee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_votes');
    }
};
