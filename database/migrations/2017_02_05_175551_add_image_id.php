<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageId extends Migration
{
    public function up()
    {
        Schema::table('elements', function($table) {
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
        Schema::table('elements', function($table) {
            $table->dropColumn('image_id');
        });
    }
}
