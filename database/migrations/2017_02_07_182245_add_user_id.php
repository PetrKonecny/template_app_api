<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function($table) {
            $table->integer('user_id');
        });

        Schema::table('template_instances', function($table) {
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function($table) {
            $table->dropColumn('user_id');
        });

        Schema::table('template_instances', function($table) {
            $table->dropColumn('user_id');
        });
    }
}
