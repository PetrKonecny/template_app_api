<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFontIdColumn extends Migration
{
    public function up()
    {
        Schema::table('elements', function($table) {
            $table->integer('font_id');
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
            $table->dropColumn('font_id');
        });
    }
}


