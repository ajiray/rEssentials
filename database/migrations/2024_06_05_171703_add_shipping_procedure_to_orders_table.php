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
            $table->text('shipping_procedure')->nullable()->after('shipping_status');
            $table->string('receipt')->nullable()->after('shipping_procedure');
            $table->unsignedInteger('num_orders')->default(1)->after('receipt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_procedure');
            $table->dropColumn('receipt');
            $table->dropColumn('num_orders');
        });
    }
};
