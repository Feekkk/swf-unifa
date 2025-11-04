<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Category and Subcategory
            $table->string('category'); // bereavement, illness, emergency
            $table->string('subcategory'); // student, parent, sibling, outpatient, inpatient, injuries, critical, disaster, others
            
            // Dynamic fields (nullable, stored as JSON for flexibility)
            $table->decimal('amount_applied', 10, 2)->nullable(); // For editable amounts
            $table->json('application_data')->nullable(); // Store all dynamic form data
            
            // Bank Details
            $table->string('bank_name');
            $table->string('bank_account_number');
            
            // Application Status (includes 'verify' status)
            $table->enum('status', ['pending', 'verify', 'under_review', 'approved', 'rejected', 'disbursed'])->default('pending');
            
            // Admin fields
            $table->text('admin_notes')->nullable();
            $table->text('committee_remarks')->nullable();
            $table->decimal('amount_approved', 10, 2)->nullable();
            $table->timestamp('reviewed_at')->nullable();
            
            // Foreign keys
            // Note: reviewed_by will reference committees (added after committees table is created)
            // verified_by will reference admins (added after admins table is created)
            $table->foreignId('reviewed_by')->nullable();
            $table->foreignId('verified_by')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
