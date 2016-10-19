<?php
/** actuallymab | 12.06.2016 - 14:39 */

class Users extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name', 100);
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}
