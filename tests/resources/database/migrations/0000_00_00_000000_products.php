<?php
/** actuallymab | 12.06.2016 - 14:41 */

class Products extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        Schema::create('products', function ($table) {
            $table->increments('id');
            $table->string('name', 100);
        });
    }

    public function down()
    {
        Schema::drop('products');
    }
}
