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
    Schema::table('product_variants', function (Blueprint $table) {
        $table->text('desc')->nullable(); // Add desc column as nullable text
    });
}

public function down()
{
    Schema::table('product_variants', function (Blueprint $table) {
        $table->dropColumn('desc');
    });
}
};
