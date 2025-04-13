<?php

use App\Traits\CommonMigrationTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use CommonMigrationTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
//        Schema::create('payment_gateways', function (Blueprint $table) {
//            $table->id();
//            $table->string('name');
//            $table->string('slug')->unique();
//            $table->string('account_id')->nullable();
//            $table->string('client_id')->nullable();
//            $table->string('client_secret')->nullable();
//            $table->string('api_url')->nullable()->unique();
//            $table->string('redirect_url')->nullable();
//            $table->string('cancel_url')->nullable();
//            $table->string('failed_url')->nullable();
//            $table->string('description')->nullable();
//            $table->text('instruction')->nullable();
//            $table->text('settings')->nullable();
//            $table->string('mode')->default('online');
//            $table->string('status')->default(0);
//            $this->empExtracted($table);
//        });

        Schema::dropIfExists('payments');
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('reference_id')->nullable();
            $table->string('ref')->nullable();
            $table->string('payment_method')->nullable();//momo/visa
            $table->date('payment_date')->nullable();//momo/visa
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_type')->nullable(); //mtn for momo
            $table->string('merchant_name')->nullable(); //for banks
            $table->decimal('balance', 18, 2)->default(0.00);
            $table->decimal('amount', 18, 2)->default(0.00);
            $table->decimal('charge', 18, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->string('status')->nullable();

            $table->bigInteger('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->bigInteger('payment_gateway_id')->unsigned()->index()->nullable();
            $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways');

            $table->bigInteger('invoice_id')->unsigned()->index();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $this->empExtracted($table);
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
