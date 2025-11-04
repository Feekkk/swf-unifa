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
        // Add 'verify' to the status enum
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('pending', 'verify', 'under_review', 'approved', 'rejected', 'disbursed') DEFAULT 'pending'");
        
        // Add verified_by column (references admins table)
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('verified_by')->nullable()->after('reviewed_by')->constrained('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove verified_by column
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn('verified_by');
        });
        
        // Remove 'verify' from the status enum
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('pending', 'under_review', 'approved', 'rejected', 'disbursed') DEFAULT 'pending'");
    }
};
