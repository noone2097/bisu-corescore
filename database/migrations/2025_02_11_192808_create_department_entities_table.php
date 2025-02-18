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
        Schema::create('department_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add department_entity_id to department_accounts table if it exists
        if (Schema::hasTable('department_accounts')) {
            Schema::table('department_accounts', function (Blueprint $table) {
                $table->foreign('department_entity_id')
                    ->references('id')
                    ->on('department_entities')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('department_accounts')) {
            Schema::table('department_accounts', function (Blueprint $table) {
                $table->dropForeign(['department_entity_id']);
                $table->dropColumn('department_entity_id');
            });
        }

        Schema::dropIfExists('department_entities');
    }
};