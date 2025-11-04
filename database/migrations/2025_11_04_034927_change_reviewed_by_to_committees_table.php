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
        Schema::table('applications', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['reviewed_by']);
        });

        Schema::table('applications', function (Blueprint $table) {
            // Add new foreign key constraint to committees table
            $table->foreign('reviewed_by')->references('id')->on('committees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop the committees foreign key
            $table->dropForeign(['reviewed_by']);
        });

        Schema::table('applications', function (Blueprint $table) {
            // Restore the old foreign key to users table
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
