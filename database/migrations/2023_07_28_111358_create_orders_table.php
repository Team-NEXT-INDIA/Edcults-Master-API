<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('tracking_id');
            $table->string('bank_ref_no')->nullable();
            $table->string('order_status');
            $table->string('failure_message')->nullable();
            $table->string('payment_mode');
            $table->string('card_name');
            $table->string('status_code')->nullable();
            $table->string('status_message');
            $table->string('currency');
            $table->decimal('amount', 8, 2);
            $table->string('billing_name')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zip')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_tel')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('delivery_name')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_state')->nullable();
            $table->string('delivery_zip')->nullable();
            $table->string('delivery_country')->nullable();
            $table->string('delivery_tel')->nullable();
            $table->string('merchant_param1')->nullable();
            $table->string('merchant_param2')->nullable();
            $table->string('merchant_param3')->nullable();
            $table->string('merchant_param4')->nullable();
            $table->string('merchant_param5')->nullable();
            $table->string('vault');
            $table->string('offer_type')->nullable();
            $table->string('offer_code')->nullable();
            $table->decimal('discount_value', 8, 2);
            $table->decimal('mer_amount', 8, 2);
            $table->string('eci_value')->nullable();
            $table->string('retry');
            $table->string('response_code')->nullable();
            $table->text('billing_notes')->nullable();
            $table->string('trans_date');
            $table->string('bin_country')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
