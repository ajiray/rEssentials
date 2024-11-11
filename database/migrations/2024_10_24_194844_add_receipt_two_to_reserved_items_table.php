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
        Schema::table('reserved_items', function (Blueprint $table) {
            $table->string('receipt_two')->nullable()->after('receipt'); // Adds the receipt_two column after the existing receipt column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserved_items', function (Blueprint $table) {
            $table->dropColumn('receipt_two'); // Removes the receipt_two column
        });
    }
};
