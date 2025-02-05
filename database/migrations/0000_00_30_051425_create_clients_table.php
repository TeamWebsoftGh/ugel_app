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
        Schema::create('client_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('category')->default("individual");
            $table->string('description')->nullable();

            $this->empExtracted($table);
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('other_names')->nullable();
            $table->string('email')->nullable();
            $table->string('email_cc')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('alternate_phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Business-specific fields
            $table->string('business_name')->nullable();
            $table->string('business_telephone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('website')->nullable();
            $table->string('certificate_of_incorporation')->nullable();
            $table->string('number_of_employees')->nullable();
            $table->string('date_of_incorporation')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('type_of_business')->nullable();

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('status')->default("active");
            $table->text('comment')->nullable();


            $table->string('terms_and_condition')->nullable();
            $table->unsignedBigInteger('account_officer_id')->nullable();
            $table->string('referral_code')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();

            $table->unsignedBigInteger('client_type_id')->index();
            $table->foreign('client_type_id')->references('id')->on('client_types');
            $table->foreign('country_id')->references('id')->on('countries');

            $this->empExtracted($table);
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('account_number');
            $table->string('account_branch')->nullable();
            $table->string('branch_code')->nullable();

            $table->unsignedBigInteger('bank_id');
            $table->foreign('bank_id')->references('id')->on('banks');

            $table->unsignedBigInteger('client_id')->index();
            $table->foreign('client_id')->references('id')->on('clients');

            $this->empExtracted($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
