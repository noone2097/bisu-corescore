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
        Schema::create('faculty_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('evaluation_period_id')->constrained('evaluation_periods')->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            
            // Prevent duplicate assignments in the same evaluation period
            $table->unique(['faculty_id', 'course_id', 'evaluation_period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_courses');
    }
};
