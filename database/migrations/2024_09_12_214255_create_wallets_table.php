<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use \App\Traits\CommonMigrationTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 18, 2)->default(0.00);
            $table->decimal('actual_balance', 18, 2)->default(0.00);
            $table->bigInteger('walletable_id')->unsigned()->index();
            $table->string('walletable_type')->index()->nullable();

            $this->empExtracted($table);
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->string('description');
            $table->string('transactionable_type');
            $table->bigInteger('transactionable_id');
            $table->decimal('amount', 18, 2);
            $table->decimal('credit', 18, 2);
            $table->decimal('debit', 18, 2);
            $table->decimal('balance', 18, 2);

            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->foreign('wallet_id')->references('id')->on('wallets');

            $this->empExtracted($table);
            $table->index(['transactionable_type', 'transactionable_id'], 'transactionable');
            $table->index(['wallet_id', 'transactionable_type', 'transactionable_id'], 'wallet_transactionable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
