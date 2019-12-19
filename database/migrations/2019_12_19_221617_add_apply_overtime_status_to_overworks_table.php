<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplyOvertimeStatusToOverworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overworks', function (Blueprint $table) {
          $table->string('apply_overtime_status')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overworks', function (Blueprint $table) {
          $table->dropColumn('apply_overtime_status');
        });
    }
}
