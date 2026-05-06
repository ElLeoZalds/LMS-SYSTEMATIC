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
        Schema::create('trainings', function (Blueprint $table) {

            $table->id('training_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('administrator_id');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('teacher_id')->references('user_id')->on('users')->onDelete('restrict');
            $table->foreign('administrator_id')->references('user_id')->on('users')->onDelete('restrict');
            $table->enum('modality', ['virtual', 'presential', 'hybrid']);
            $table->decimal('price', 10, 2);
            $table->date('creation_date');
            $table->char('status', 1)->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};