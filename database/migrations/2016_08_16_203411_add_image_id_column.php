<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageIdColumn extends Migration
{
    public function up()
    {
        Schema::table('contents', function($table) {
            $table->integer('image_id');
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
            $table->dropColumn('image_id');
        });
    }
}


