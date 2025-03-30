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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('year_level_id')->constrained('year_levels')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->timestamps();
        });

        $coursesByDepartment = [
            'DCS' => [
                ['code' => 'CS101', 'name' => 'Introduction to Computer Science', 'year' => 1],
                ['code' => 'CS201', 'name' => 'Data Structures and Algorithms', 'year' => 2],
                ['code' => 'CS301', 'name' => 'Software Engineering', 'year' => 3],
                ['code' => 'CS401', 'name' => 'Advanced Web Development', 'year' => 4],
            ],
            'CTE' => [
                ['code' => 'ED101', 'name' => 'Foundations of Education', 'year' => 1],
                ['code' => 'ED201', 'name' => 'Educational Psychology', 'year' => 2],
                ['code' => 'ED301', 'name' => 'Curriculum Development', 'year' => 3],
                ['code' => 'ED401', 'name' => 'Teaching Methods and Strategies', 'year' => 4],
            ],
            'COM' => [
                ['code' => 'MW101', 'name' => 'Introduction to Midwifery', 'year' => 1],
                ['code' => 'MW201', 'name' => 'Maternal Care', 'year' => 2],
                ['code' => 'MW301', 'name' => 'Advanced Obstetrics', 'year' => 3],
                ['code' => 'MW401', 'name' => 'Clinical Midwifery Practice', 'year' => 4],
            ],
            'BIndTech-FPST' => [
                ['code' => 'FP101', 'name' => 'Introduction to Food Technology', 'year' => 1],
                ['code' => 'FP201', 'name' => 'Food Processing and Preservation', 'year' => 2],
                ['code' => 'FP301', 'name' => 'Food Service Management', 'year' => 3],
                ['code' => 'FP401', 'name' => 'Advanced Food Production', 'year' => 4],
            ],
            'BIndTech-ET' => [
                ['code' => 'ET101', 'name' => 'Basic Electrical Theory', 'year' => 1],
                ['code' => 'ET201', 'name' => 'Electrical Circuits and Systems', 'year' => 2],
                ['code' => 'ET301', 'name' => 'Power Systems and Control', 'year' => 3],
                ['code' => 'ET401', 'name' => 'Advanced Electrical Technology', 'year' => 4],
            ],
        ];

        $departments = DB::table('departments')->get();
        foreach ($departments as $dept) {
            $courses = $coursesByDepartment[$dept->code];
            foreach ($courses as $course) {
                DB::table('courses')->insert([
                    'code' => $course['code'],
                    'name' => $course['name'],
                    'description' => 'Course for ' . $course['name'],
                    'year_level_id' => $course['year'],
                    'department_id' => $dept->id,
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
        Schema::dropIfExists('courses');
    }
};
