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
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->string('full_name')->after('id');
            $table->string('username', 20)->unique()->after('full_name');
            $table->string('bank_name', 100)->after('email');
            $table->string('bank_account_number', 20)->after('bank_name');
            
            // Contact Information
            $table->string('phone_number', 20)->after('bank_account_number');
            $table->string('street_address')->after('phone_number');
            $table->string('city', 100)->after('street_address');
            $table->string('state', 100)->after('city');
            $table->string('postal_code', 5)->after('state');
            
            // Academic Information
            $table->string('student_id', 20)->unique()->after('postal_code');
            $table->string('course', 100)->after('student_id');
            $table->integer('semester')->after('course');
            $table->integer('year_of_study')->after('semester');
            
            // Status
            $table->boolean('is_active')->default(true)->after('remember_token');
            
            // Drop the old 'name' column if it exists
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn([
                'full_name',
                'username',
                'bank_name',
                'bank_account_number',
                'phone_number',
                'street_address',
                'city',
                'state',
                'postal_code',
                'student_id',
                'course',
                'semester',
                'year_of_study',
                'is_active'
            ]);
            
            // Add back the 'name' column
            $table->string('name')->after('id');
        });
    }
};
