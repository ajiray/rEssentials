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
            // Change the status column to a string
            $table->string('status')->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('reserved_items', function (Blueprint $table) {
            // Revert the status column back to enum with the original options
            $table->enum('status', ['Pending', 'Confirmed', 'Cancelled', 'Completed'])
                  ->default('Pending')->change();
        });
    }
};