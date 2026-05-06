<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {

            $table->id('enrollment_id');
            $table->unique(['training_id', 'student_id']);
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('administrator_id');
            $table->foreign('training_id')->references('training_id')->on('trainings')->onDelete('cascade');
            $table->foreign('student_id')->references('user_id')->on('users')->onDelete('restrict');
            $table->foreign('administrator_id')->references('user_id')->on('users')->onDelete('restrict');
            $table->date('enrollment_date');
            $table->decimal('scholarship_percentage', 5, 2)->nullable();
            $table->char('status', 1)->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};