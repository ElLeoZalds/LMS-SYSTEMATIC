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
        Schema::create('payments', function (Blueprint $table) {

            $table->id('payment_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('enrollment_id')->references('enrollment_id')->on('enrollments')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('method_id')->on('payment_methods')->onDelete('restrict');
            $table->date('date');
            $table->decimal('installment', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->char('status', 1)->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};