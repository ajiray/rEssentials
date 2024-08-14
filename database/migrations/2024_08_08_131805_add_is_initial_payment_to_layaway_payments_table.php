<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsInitialPaymentToLayawayPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('layaway_payments', function (Blueprint $table) {
            $table->boolean('is_initial_payment')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('layaway_payments', function (Blueprint $table) {
            $table->dropColumn('is_initial_payment');
        });
    }
}

