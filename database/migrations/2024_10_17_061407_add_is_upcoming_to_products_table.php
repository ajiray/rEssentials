<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->boolean('is_upcoming')->default(false); // Default to false, meaning the product is not upcoming by default
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('is_upcoming');
    });
}
};
