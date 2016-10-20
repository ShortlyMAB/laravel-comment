<?php
/** actuallymab | 12.06.2016 - 02:00 */

class Comments extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        Schema::create('comments', function ($table) {
            $table->increments('id');
            $table->integer('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
            $table->integer('commented_id')->nullable();
            $table->string('commented_type')->nullable();
            $table->longText('comment');
            $table->boolean('approved')->default(true);
            $table->double('rate', 15, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('comments');
    }
}
