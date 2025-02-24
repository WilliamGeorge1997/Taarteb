<?php

use Modules\Grade\App\Models\Grade;
use Modules\School\App\Models\School;
use Illuminate\Support\Facades\Schema;
use Modules\Subject\App\Models\Subject;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->foreignIdFor(Subject::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Grade::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(School::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->rememberToken();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
