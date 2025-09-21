<?php

use Illuminate\Support\Facades\Schema;
use Modules\Expense\App\Models\Expense;
use Modules\Student\App\Models\Student;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Expense::class)->index()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount')->default(0);
            $table->date('date')->nullable();
            $table->string('receipt')->nullable();
            $table->string('rejected_reason')->nullable();
            $table->enum('status', ['pending', 'partial', 'paid', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_expenses');
    }
};
