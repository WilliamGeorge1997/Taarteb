<?php

use Modules\Grade\App\Models\Grade;
use Illuminate\Support\Facades\Schema;
use Modules\Class\App\Models\Classroom;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['m', 'f']);
            $table->string('email')->unique();
            $table->string('identity_number')->unique();
            $table->foreignIdFor(Classroom::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Grade::class)->index()->constrained()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
