<?php

use Modules\School\App\Models\School;
use Illuminate\Support\Facades\Schema;
use Modules\Class\App\Models\Classroom;
use Modules\Session\App\Models\Session;
use Modules\Student\App\Models\Student;
use Modules\Subject\App\Models\Subject;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Teacher\App\Models\TeacherProfile;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->enum('day', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->integer('session_number');
            $table->enum('semester', ['first', 'second']);
            $table->integer('year');
            $table->foreignIdFor(TeacherProfile::class, 'teacher_id')->index()->constrained('teacher_profiles')->restrictOnDelete();
            $table->foreignIdFor(TeacherProfile::class, 'attendance_taken_by')->nullable()->index()->constrained('teacher_profiles')->restrictOnDelete();
            $table->foreignIdFor(Student::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Subject::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Session::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Classroom::class, 'class_id')->index()->constrained('classes')->restrictOnDelete();
            $table->foreignIdFor(School::class)->index()->constrained()->restrictOnDelete();
            $table->boolean('is_present')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
