<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorColumns extends Migration
{
    public function up()
    {
        Schema::table('elements', function($table) {
            $table->string('background_color');
            $table->string('text_color');
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
            $table->dropColumn('background_color');
            $table->dropColumn('text_color');
        });
    }
}
