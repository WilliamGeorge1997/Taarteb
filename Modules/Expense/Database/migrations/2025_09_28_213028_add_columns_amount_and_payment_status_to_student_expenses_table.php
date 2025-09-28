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
        Schema::table('student_expenses', function (Blueprint $table) {
            $table->unsignedInteger('amount')->after('expense_id')->nullable();
            $table->unsignedInteger('amount_paid')->after('amount')->nullable();
            $table->enum('payment_status', ['partial', 'full'])->after('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_expenses', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('payment_status');
            $table->dropColumn('amount_paid');
        });
    }
};
