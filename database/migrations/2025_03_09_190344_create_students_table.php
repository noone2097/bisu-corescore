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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('studentID')->unique();
            $table->string('name');
            $table->enum('gender', ['male','female'])->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_active')->default(false);
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('program');
            $table->foreignId('year_level_id')->constrained()->restrictOnDelete();
            $table->enum('student_type', ['regular','irregular']);
            // OAuth columns
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->text('provider_token')->nullable();
            $table->text('provider_refresh_token')->nullable();
            $table->timestamps();
        });

        $departmentPrograms = [
            'DCS' => 'Bachelor of Science in Computer Science',
            'CTE' => 'Bachelor of Education',
            'COM' => 'Bachelor of Science in Midwifery',
            'BIndTech-FPST' => 'Bachelor of Industrial Technology - Food Preparation and Services Technology',
            'BIndTech-ET' => 'Bachelor of Industrial Technology - Electrical Technology'
        ];

        $departments = DB::table('departments')->get();
        
        foreach ($departments as $dept) {
            // Create one student for each year level in each department
            for ($year = 1; $year <= 4; $year++) {
                // Generate unique 6 digit student ID
                do {
                    $studentID = str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
                    $exists = DB::table('students')
                        ->where('studentID', $studentID)
                        ->exists();
                } while ($exists);

                // Randomly select gender
                $gender = rand(0, 1) ? 'male' : 'female';
                
                DB::table('students')->insert([
                    'avatar' => null,
                    'studentID' => $studentID,
                    'name' => "Student {$year} - {$dept->name}",
                    'gender' => $gender,
                    'email' => strtolower("{$studentID}@bisu.edu.ph"),
                    'email_verified_at' => now(),
                    'password' => bcrypt('student-pass'),
                    'is_active' => true,
                    'department_id' => $dept->id,
                    'program' => $departmentPrograms[$dept->code],
                    'year_level_id' => $year,
                    'student_type' => 'regular',
                    'created_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('year_level_id');
            $table->dropColumn([
                'provider',
                'provider_id',
                'provider_token',
                'provider_refresh_token',
                'reset_token',
            ]);
        });
        Schema::dropIfExists('students');
    }
};
