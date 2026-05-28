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
        Schema::create('announcements', function (Blueprint $table) {
            $table->increments('announcement_id');
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('teacher_id');
            $table->text('content');
            $table->string('link')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->foreign('training_id')->references('training_id')->on('trainings')->onDelete('cascade');
            $table->foreign('teacher_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
