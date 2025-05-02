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
        Schema::table('attendance', function (Blueprint $table) {
            $table->timestamp('break_start')->nullable()->after('check_out');
            $table->timestamp('break_end')->nullable()->after('break_start');
            $table->text('breaks')->nullable()->after('break_end');
            $table->boolean('multiple_breaks')->default(false)->after('breaks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropColumn('break_start');
            $table->dropColumn('break_end');
            $table->dropColumn('breaks');
            $table->dropColumn('multiple_breaks');
        });
    }
};