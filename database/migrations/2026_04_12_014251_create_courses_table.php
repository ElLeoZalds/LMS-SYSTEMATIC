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

            $table->id('course_id');
            $table->unsignedBigInteger('specialty_id');
            $table->foreign('specialty_id')->references('specialty_id')->on('specialties')->onDelete('restrict');
            $table->string('banner_path')->nullable();
            $table->string('title', 150);
            $table->string('abbreviation', 10)->unique();
            $table->text('description')->nullable();
            $table->integer('hours_count')->nullable();
            $table->decimal('reference_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
