<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_visitors_table.php
public function up()
{
    Schema::create('visitors', function (Blueprint $table) {
        $table->id();
        $table->string('ip_address');
        $table->string('user_agent');
        $table->string('device');
        $table->string('platform');
        $table->string('browser');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('visitors');
}

};
