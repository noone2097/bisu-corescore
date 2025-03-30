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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->softDeletes();
            $table->timestamps();
        });

        $departments = [
            [
                'name' => 'Department of Computer Science',
                'code' => 'DCS',
                'created_at' => now(),
            ],
            [
                'name' => 'College of Teachers Education',
                'code' => 'CTE',
                'created_at' => now(),
            ],
            [
                'name' => 'College of Midwifery',
                'code' => 'COM',
                'created_at' => now(),
            ],
            [
                //food preparation and services technology
                'name' => 'BIndTech-FPST',
                'code' => 'BIndTech-FPST',
                'created_at' => now(),
            ],
            [
                //this is electrical technology
                'name' => 'BIndTech-ET',
                'code' => 'BIndTech-ET',
                'created_at' => now(),
            ],
            

        ];

        foreach ($departments as $department) {
            DB::table('departments')->insert($department);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
