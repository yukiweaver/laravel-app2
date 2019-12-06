<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->time('designate_start_time')->nullable()->change();
            $table->time('designate_end_time')->nullable()->change();
            $table->time('basic_work_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->datetime('designate_start_time')->nullable()->change();
          $table->datetime('designate_end_time')->nullable()->change();
          $table->datetime('basic_work_time')->nullable()->change();
        });
    }
}
