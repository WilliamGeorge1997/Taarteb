<?php

use Illuminate\Support\Facades\Schema;
use Modules\Student\App\Models\Student;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->index()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('payment_method')->comment('1: cash, 2: visa 3:online payment');
            $table->unsignedDecimal('amount', 10, 2)->nullable();
            $table->enum('payment_status', ['paid', 'pending', 'failed'])->default('pending');
            $table->string('receipt')->nullable();
            $table->enum('status', ['accepted', 'rejected', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_fees');
    }
};
