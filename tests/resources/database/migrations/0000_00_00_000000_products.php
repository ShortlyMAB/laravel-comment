<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->boolean('can_be_rated')->default(false);
            $table->boolean('must_be_approved')->default(false);
        });
    }

    public function down(): void
    {
        Schema::drop('products');
    }
}
