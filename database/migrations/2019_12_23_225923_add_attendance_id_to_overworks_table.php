<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttendanceIdToOverworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overworks', function (Blueprint $table) {
          $table->bigInteger('attendance_id')->unsigned();
          $table->foreign('attendance_id')->references('id')->on('attendances');
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
          $table->dropForeign('overworks_attendance_id_foreign');
        });
    }
}
