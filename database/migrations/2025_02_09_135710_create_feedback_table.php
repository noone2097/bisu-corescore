<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->onDelete('cascade');
            $table->foreignId('office_id')->constrained('users')->onDelete('cascade');
            $table->date('date_of_visit');
            $table->time('time_of_visit');
            $table->string('client_type');
            $table->string('sex');
            $table->string('region_of_residence');
            $table->string('services_availed');
            $table->string('served_by');
            // CC Questions
            $table->integer('cc1');
            $table->integer('cc2')->nullable();
            $table->integer('cc3')->nullable();
            // Ratings
            $table->integer('responsiveness');
            $table->integer('reliability');
            $table->integer('access_facilities');
            $table->integer('communication');
            $table->integer('costs');
            $table->integer('integrity');
            $table->integer('assurance');
            $table->integer('outcome');
            // Comments
            $table->text('commendations')->nullable();
            $table->text('suggestions')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('exported_by')->nullable()->constrained('users');
            $table->timestamp('exported_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropForeign(['exported_by']);
            $table->dropColumn('exported_by');
            $table->dropColumn('exported_at');
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('feedback');
    }
};
