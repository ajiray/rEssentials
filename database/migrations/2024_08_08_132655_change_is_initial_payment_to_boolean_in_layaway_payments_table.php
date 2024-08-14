<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIsInitialPaymentToBooleanInLayawayPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layaway_payments', function (Blueprint $table) {
            $table->boolean('is_initial_payment')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('layaway_payments', function (Blueprint $table) {
            $table->tinyInteger('is_initial_payment')->default(0)->change();
        });
    }
}

