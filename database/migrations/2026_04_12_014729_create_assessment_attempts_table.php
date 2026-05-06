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
        Schema::create('assessment_attempts', function (Blueprint $table) {

            $table->id('attempt_id');
            $table->unique(['enrollment_id', 'assessment_id', 'number']);
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('enrollment_id')->references('enrollment_id')->on('enrollments')->onDelete('cascade');
            $table->foreign('assessment_id')->references('assessment_id')->on('assessments')->onDelete('cascade');
            $table->integer('number');
            $table->date('date');
            $table->decimal('score', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_attempts');
    }
};