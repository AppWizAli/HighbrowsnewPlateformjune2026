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
        Schema::create('monthly_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained('admissions')->onDelete('cascade');

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');


            $table->date('date');
            $table->string('receipt_no')->unique();
            $table->date('receiving_date');
            $table->string('fee_month', 10); // Format: YYYY-MM
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('discounted_tuition_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('amount_words')->nullable();
            $table->string('receiver_name')->nullable();
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
        Schema::dropIfExists('monthley_fees');
    }
};
