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
        Schema::create('people', function (Blueprint $table) {
            $table->id('person_id');
            $table->string('last_names', 20);
            $table->string('first_names', 20);
            $table->string('document_type', 20)->nullable();
            $table->string('document_number', 20)->nullable()->unique();
            $table->string('phone', 9)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('email', 150)->nullable()->unique();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->date('birth_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};