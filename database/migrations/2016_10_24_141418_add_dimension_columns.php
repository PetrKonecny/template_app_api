<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDimensionColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function($table) {
            $table->integer('left');
            $table->integer('top');
            $table->integer('width');
            $table->integer('height');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contents', function($table) {
            $table->dropColumn('left');
            $table->dropColumn('top');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
