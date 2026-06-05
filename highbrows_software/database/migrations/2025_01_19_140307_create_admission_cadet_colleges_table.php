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
    Schema::create('admission_cadet_colleges', function (Blueprint $table) {
        $table->foreignId('admission_id')->constrained('admissions')->onDelete('cascade');
        $table->foreignId('college_id')->constrained('colleges')->onDelete('cascade');

        $table->primary(['admission_id', 'college_id']); // ← This is now your only primary key

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
        Schema::dropIfExists('admission_cadet_colleges');
    }
};
