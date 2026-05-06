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
        Schema::create('attendances', function (Blueprint $table) {

            $table->id('attendance_id');
            $table->unique(['schedule_id', 'enrollment_id']);
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->onDelete('cascade');
            $table->foreign('enrollment_id')->references('enrollment_id')->on('enrollments')->onDelete('cascade');
            $table->enum('attendance', ['present', 'absent', 'late']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};