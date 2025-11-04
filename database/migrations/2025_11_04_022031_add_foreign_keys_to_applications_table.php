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
        // Add foreign key constraints after both admins and committees tables exist
        Schema::table('applications', function (Blueprint $table) {
            // Add foreign key to admins table
            $table->foreign('verified_by')->references('id')->on('admins')->onDelete('set null');
            
            // Add foreign key to committees table
            $table->foreign('reviewed_by')->references('id')->on('committees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['reviewed_by']);
        });
    }
};

