<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('account_id')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('api_url')->nullable()->unique();
            $table->string('redirect_url')->nullable();
            $table->string('cancel_url')->nullable();
            $table->string('failed_url')->nullable();
            $table->string('description')->nullable();
            $table->text('instruction')->nullable();
            $table->text('settings')->nullable();
            $table->string('mode')->default('online');
            $table->string('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('reference_id')->nullable();
            $table->string('ref')->nullable();
            $table->string('payment_method')->nullable();//momo/visa
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_type')->nullable(); //mtn for momo
            $table->string('merchant_name')->nullable(); //for banks
            $table->decimal('amount', 18, 2)->default(0.00);
            $table->decimal('charge', 18, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->bigInteger('paymentable_id')->unsigned()->index();
            $table->string('paymentable_type')->index()->nullable();

            $table->bigInteger('payment_gateway_id')->unsigned()->index()->nullable();
            $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('attachment')->nullable();
            $table->string('payment_reason');
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_type')->nullable(); //mtn for momo
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->bigInteger('withdrawable_id')->unsigned()->index();
            $table->string('withdrawable_type')->index()->nullable();

            $table->bigInteger('payment_gateway_id')->unsigned()->index()->nullable();
            $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
