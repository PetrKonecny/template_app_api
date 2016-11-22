<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowColumn extends Migration
{
    public function up()
    {
        Schema::table('elements', function($table) {
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
        Schema::table('elements', function($table) {
            $table->dropColumn('rows');
        });
    }
}
