<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameElementColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('elements', function($t) {
                        $t->renameColumn('position_x', 'positionX');
                        $t->renameColumn('position_y', 'positionY');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('elements', function($t) {
                        $t->renameColumn('positionX','position_x');
                        $t->renameColumn('positionY','position_y');
        });
    }
}
