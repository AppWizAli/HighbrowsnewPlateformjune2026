<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('student_cnic');
            $table->string('mother_cnic');
            $table->string('father_cnic');
            $table->string('guardian_name')->nullable();
            $table->string('religion');
            $table->string('pre_class');
            $table->foreignId('grade_applied_for')->constrained('classes');  // Foreign key referencing classes table
            $table->date('dob');
            $table->enum('res_type', ['Hostelite', 'Day Scholar']);
            $table->string('contact');
            $table->string('guardian_phone');
            $table->string('guardian_whatsapp');
            $table->string('domicile');
            $table->text('postal_address');
            $table->decimal('father_income', 15, 2);
            $table->string('passport_pic')->nullable();
            $table->string('b_form')->nullable();
            $table->string('father_cnic_doc')->nullable();
            $table->string('result_card')->nullable();
            $table->boolean('apply_cadet_colleges')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admissions');
    }
};
