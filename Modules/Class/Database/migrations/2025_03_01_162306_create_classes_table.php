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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Grade::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(School::class)->index()->constrained()->restrictOnDelete();
            $table->integer('max_students');
            $table->enum('period_number', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
