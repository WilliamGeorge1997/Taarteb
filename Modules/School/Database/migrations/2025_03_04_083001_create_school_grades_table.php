<?php

use Modules\Grade\App\Models\Grade;
use Modules\School\App\Models\School;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_grades', function (Blueprint $table) {
            $table->foreignIdFor(School::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Grade::class)->index()->constrained()->restrictOnDelete();
            $table->primary(['school_id', 'grade_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_grades');
    }
};
