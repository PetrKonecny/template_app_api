<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('elements', function (Blueprint $table) {
            $table->increments('id');
            $table->String('type');
            $table->Integer('width');
            $table->Integer('height');
            $table->Integer('position_x');
            $table->Integer('position_y');
            $table->Integer('rotation');
            $table->Integer('opacity');
            $table->String('border_style');
            $table->String('font_style');
            $table->Integer('font_size');
            $table->Integer('max_text_length');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('elements');
    }

}
