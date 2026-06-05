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
            Schema::table('monthly_fees', function (Blueprint $table) {
                $table->string('receipt',255)->nullable()->default(null);
                $table->string('generated_by');
            });
        }

        public function down()
        {
            Schema::table('monthly_fees', function (Blueprint $table) {
                $table->dropColumn('receipt');
            });
        }



};
