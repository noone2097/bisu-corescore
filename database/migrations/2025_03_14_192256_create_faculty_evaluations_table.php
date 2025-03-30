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
        Schema::create('faculty_evaluations', function (Blueprint $table) {
           $table->id();
           
           // Relationships
           $table->foreignId('faculty_course_id')->constrained('faculty_courses')->onDelete('restrict')->onUpdate('no action');
           $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->onUpdate('no action');
           
           // A. Commitment
           $table->integer('a1_demonstrates_sensitivity')->comment('Demonstrates sensitivity to students ability');
           $table->integer('a2_integrates_learning_objectives')->comment('Integrates learning objectives');
           $table->integer('a3_makes_self_available')->comment('Makes self available beyond official time');
           $table->integer('a4_comes_to_class_prepared')->comment('Comes to class on time and prepared');
           $table->integer('a5_keeps_accurate_records')->comment('Keeps accurate records');
           
           // B. Knowledge of Subject
           $table->integer('b1_demonstrates_mastery')->comment('Demonstrates mastery of subject matter');
           $table->integer('b2_draws_information')->comment('Draws and shares current information');
           $table->integer('b3_integrates_subject')->comment('Integrates subject to practical circumstances');
           $table->integer('b4_explains_relevance')->comment('Explains relevance of present topics');
           $table->integer('b5_demonstrates_up_to_date')->comment('Demonstrates up-to-date knowledge');
           
           // C. Teaching for Independent Learning
           $table->integer('c1_creates_teaching_strategies')->comment('Creates interactive teaching strategies');
           $table->integer('c2_enhances_self_esteem')->comment('Enhances student self-esteem');
           $table->integer('c3_allows_student_creation')->comment('Allows students to create their own course');
           $table->integer('c4_allows_independent_thinking')->comment('Allows independent thinking');
           $table->integer('c5_encourages_extra_learning')->comment('Encourages learning beyond requirements');
           
           // D. Management of Learning
           $table->integer('d1_creates_opportunities')->comment('Creates opportunities for contributions');
           $table->integer('d2_assumes_various_roles')->comment('Assumes roles as facilitator, coach, etc');
           $table->integer('d3_designs_learning')->comment('Designs learning conditions');
           $table->integer('d4_structures_learning')->comment('Structures learning context');
           $table->integer('d5_uses_instructional_materials')->comment('Uses instructional materials');
           
           // Comments
           $table->text('comments')->nullable();
           $table->longText('signature')->nullable();
           
           $table->timestamps();
           $table->softDeletes();
           $table->foreignId('exported_by')->nullable()->constrained('users')->onDelete('no action')->onUpdate('no action');
           $table->timestamp('exported_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_evaluations');
    }
};
