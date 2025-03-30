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
                ['code' => 'CS102', 'name' => 'Programming Fundamentals', 'year' => 1],
                ['code' => 'CS103', 'name' => 'Computer Organization', 'year' => 1],
                ['code' => 'CS201', 'name' => 'Data Structures and Algorithms', 'year' => 2],
                ['code' => 'CS202', 'name' => 'Database Management Systems', 'year' => 2],
                ['code' => 'CS203', 'name' => 'Object-Oriented Programming', 'year' => 2],
                ['code' => 'CS301', 'name' => 'Software Engineering', 'year' => 3],
                ['code' => 'CS302', 'name' => 'Web Technologies', 'year' => 3],
                ['code' => 'CS303', 'name' => 'Network Programming', 'year' => 3],
                ['code' => 'CS401', 'name' => 'Advanced Web Development', 'year' => 4],
                ['code' => 'CS402', 'name' => 'Artificial Intelligence', 'year' => 4],
                ['code' => 'CS403', 'name' => 'Cybersecurity', 'year' => 4],
            ],
            'CTE' => [
                ['code' => 'ED101', 'name' => 'Foundations of Education', 'year' => 1],
                ['code' => 'ED102', 'name' => 'Child Development', 'year' => 1],
                ['code' => 'ED103', 'name' => 'Teaching and Learning Principles', 'year' => 1],
                ['code' => 'ED201', 'name' => 'Educational Psychology', 'year' => 2],
                ['code' => 'ED202', 'name' => 'Classroom Management', 'year' => 2],
                ['code' => 'ED203', 'name' => 'Assessment in Learning', 'year' => 2],
                ['code' => 'ED301', 'name' => 'Curriculum Development', 'year' => 3],
                ['code' => 'ED302', 'name' => 'Instructional Technology', 'year' => 3],
                ['code' => 'ED303', 'name' => 'Special Education', 'year' => 3],
                ['code' => 'ED401', 'name' => 'Teaching Methods and Strategies', 'year' => 4],
                ['code' => 'ED402', 'name' => 'Professional Practice', 'year' => 4],
                ['code' => 'ED403', 'name' => 'Action Research in Education', 'year' => 4],
            ],
            'COM' => [
                ['code' => 'MW101', 'name' => 'Introduction to Midwifery', 'year' => 1],
                ['code' => 'MW102', 'name' => 'Anatomy and Physiology', 'year' => 1],
                ['code' => 'MW103', 'name' => 'Basic Healthcare', 'year' => 1],
                ['code' => 'MW201', 'name' => 'Maternal Care', 'year' => 2],
                ['code' => 'MW202', 'name' => 'Pharmacology', 'year' => 2],
                ['code' => 'MW203', 'name' => 'Pregnancy Care', 'year' => 2],
                ['code' => 'MW301', 'name' => 'Advanced Obstetrics', 'year' => 3],
                ['code' => 'MW302', 'name' => 'Neonatal Care', 'year' => 3],
                ['code' => 'MW303', 'name' => 'Reproductive Health', 'year' => 3],
                ['code' => 'MW401', 'name' => 'Clinical Midwifery Practice', 'year' => 4],
                ['code' => 'MW402', 'name' => 'Community Midwifery', 'year' => 4],
                ['code' => 'MW403', 'name' => 'Emergency Obstetrics', 'year' => 4],
            ],
            'BIndTech-FPST' => [
                ['code' => 'FP101', 'name' => 'Introduction to Food Technology', 'year' => 1],
                ['code' => 'FP102', 'name' => 'Food Chemistry', 'year' => 1],
                ['code' => 'FP103', 'name' => 'Food Safety and Sanitation', 'year' => 1],
                ['code' => 'FP201', 'name' => 'Food Processing and Preservation', 'year' => 2],
                ['code' => 'FP202', 'name' => 'Quality Control', 'year' => 2],
                ['code' => 'FP203', 'name' => 'Food Microbiology', 'year' => 2],
                ['code' => 'FP301', 'name' => 'Food Service Management', 'year' => 3],
                ['code' => 'FP302', 'name' => 'Product Development', 'year' => 3],
                ['code' => 'FP303', 'name' => 'Food Packaging', 'year' => 3],
                ['code' => 'FP401', 'name' => 'Advanced Food Production', 'year' => 4],
                ['code' => 'FP402', 'name' => 'Industrial Food Production', 'year' => 4],
                ['code' => 'FP403', 'name' => 'Research and Development', 'year' => 4],
            ],
            'BIndTech-ET' => [
                ['code' => 'ET101', 'name' => 'Basic Electrical Theory', 'year' => 1],
                ['code' => 'ET102', 'name' => 'Electronics Fundamentals', 'year' => 1],
                ['code' => 'ET103', 'name' => 'Circuit Analysis', 'year' => 1],
                ['code' => 'ET201', 'name' => 'Electrical Circuits and Systems', 'year' => 2],
                ['code' => 'ET202', 'name' => 'Digital Electronics', 'year' => 2],
                ['code' => 'ET203', 'name' => 'Power Generation', 'year' => 2],
                ['code' => 'ET301', 'name' => 'Power Systems and Control', 'year' => 3],
                ['code' => 'ET302', 'name' => 'Industrial Electronics', 'year' => 3],
                ['code' => 'ET303', 'name' => 'Electrical Installation', 'year' => 3],
                ['code' => 'ET401', 'name' => 'Advanced Electrical Technology', 'year' => 4],
                ['code' => 'ET402', 'name' => 'Renewable Energy Systems', 'year' => 4],
                ['code' => 'ET403', 'name' => 'Automation and Control', 'year' => 4],
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
