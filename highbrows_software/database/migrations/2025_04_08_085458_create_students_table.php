<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('full_name');
            $table->string('father_name');
            $table->string('mother_name')->nullable();
            $table->string('father_cnic');
            $table->string('mother_cnic')->nullable();
            $table->string('student_cnic')->nullable();
            $table->string('guardian_name')->nullable();

            $table->string('religion')->nullable();
            $table->string('sect')->nullable();
            $table->date('dob');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('contact_number');
            $table->string('domicile_district')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
}
