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
        Schema::table('expense_student_exceptions', function (Blueprint $table) {
            $table->string('notes')->after('exception_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_student_exceptions', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
