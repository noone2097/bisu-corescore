<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('gender',['Male', 'Female'])->nullable();  
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('role', ['research-admin', 'office-admin', 'department', 'faculty', 'office']);
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add base admin accounts
        foreach (['research-admin', 'office-admin'] as $role) {
            DB::table('users')->insert([
                'avatar' => null,
                'name' => str_replace('-', ' ', ucwords($role, '-')),
                'email' => $role . '@bisu.edu.ph',
                'gender' => null,
                'email_verified_at' => now(),
                'password' => bcrypt('admin-pass'),
                'role' => $role,
                'is_active' => true,
            ]);
        }

        // Create department and faculty accounts
        $departments = DB::table('departments')->get();
        foreach ($departments as $dept) {
            // Department admin
            DB::table('users')->insert([
                'avatar' => null,
                'name' => $dept->name . ' Admin',
                'email' => strtolower($dept->code) . '@bisu.edu.ph',
                'gender' => null,
                'email_verified_at' => now(),
                'password' => bcrypt('admin-pass'),
                'role' => 'department',
                'department_id' => $dept->id,
                'is_active' => true,
            ]);

            // 4 faculty members
            for ($i = 1; $i <= 4; $i++) {
                DB::table('users')->insert([
                    'avatar' => null,
                    'name' => "Faculty {$i} - {$dept->code}",
                    'email' => strtolower($dept->code) . ".faculty{$i}@bisu.edu.ph",
                    'gender' => null,
                    'email_verified_at' => now(),
                    'password' => bcrypt('faculty-pass'),
                    'role' => 'faculty',
                    'department_id' => $dept->id,
                    'is_active' => true,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('users');
    }
};
