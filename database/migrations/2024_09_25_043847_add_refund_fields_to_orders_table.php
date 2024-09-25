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
    Schema::table('orders', function (Blueprint $table) {
        $table->text('refund_reason')->nullable()->after('refund_status');
        $table->string('refund_payment_method')->nullable()->after('refund_reason');
        $table->string('refund_payment_details')->nullable()->after('refund_payment_method');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['refund_reason', 'refund_payment_method', 'refund_payment_details']);
    });
}
};
