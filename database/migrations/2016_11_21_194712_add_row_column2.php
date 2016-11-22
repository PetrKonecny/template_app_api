<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowColumn2 extends Migration
{
    public function up()
    {
        Schema::table('contents', function($table) {
            $table->text('rows');
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
            $table->dropColumn('rows');
        });
    }
}
