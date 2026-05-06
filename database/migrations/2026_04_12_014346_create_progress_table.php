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
        Schema::create('progress', function (Blueprint $table) {

            $table->id('progress_id');
            $table->unique(['enrollment_id', 'content_id']);
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('content_id');
            $table->foreign('enrollment_id')->references('enrollment_id')->on('enrollments')->onDelete('cascade');
            $table->foreign('content_id')->references('content_id')->on('contents')->onDelete('cascade');
            $table->decimal('percentage', 5, 2);
            $table->date('activity_date');
            $table->char('status', 1)->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};