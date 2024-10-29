<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('country', 100);
            $table->string('currency', 100);
            $table->string('code', 25);
            $table->string('symbol', 25);
            $table->boolean('symbol_first')->default(1);
            $table->integer('precision')->default(2);
            $table->string('thousand_separator', 10);
            $table->string('decimal_separator', 10);
            $table->boolean('is_active')->default(1);
            $table->boolean('is_default')->default(0);
            $table->decimal('exchange_rate')->default(1.00);
            $table->timestamps();
            $table->softDeletes();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();
        });

        Schema::create('countries', function($table)
        {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->boolean('is_active')->default(1);

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('country_id')->index();
            $table->foreign('country_id')->references('id')->on('countries');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_code')->unique();
            $table->string('company_type')->nullable();
            $table->string('trading_name')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('ssnit_no')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('email_cc')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_no')->nullable();
            $table->string('zip')->nullable();
            $table->string('ssn')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('favicon')->nullable();
            $table->boolean('is_active')->default(1);
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->boolean('built_in')->default(1);
            $table->text('enabled_modules')->nullable();

            $table->unsignedInteger('country_id')->nullable();

            $table->unsignedBigInteger('currency_id')->nullable();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }

    /**
     * @param Blueprint $table
     * @return void
     */
    function extracted(Blueprint $table): void
    {
        $table->boolean('is_active')->default(1);

        $table->unsignedBigInteger('company_id')->index();
        $table->foreign('company_id')->references('id')->on('companies');

        $table->string('created_from', 100)->nullable();
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedBigInteger('import_id')->nullable();

        $table->timestamps();
        $table->softDeletes();
    }
};
