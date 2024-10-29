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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('option_key');
            $table->longText('option_value')->nullable();
            $table->boolean('auto_load_disabled')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->string('module', 100)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('class')->nullable();
            $table->string('rules')->nullable();
            $table->string('db_columns')->nullable();
            $table->boolean('displayed')->default(1);
            $table->boolean('required')->default(0);
            $table->text('options')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('financial_years', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('pay_type'); //Monthly
            $table->integer('pay_period')->default(12);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('year')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pay_periods', function (Blueprint $table) {
            $table->id();
            $table->string('pay_month');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_open')->default(1);
            $table->boolean('is_active')->default(1);

            $table->unsignedBigInteger('financial_year_id');
            $table->foreign('financial_year_id')->references('id')->on('financial_years');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(1);

            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('document_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('original_file_name');
            $table->string('type')->nullable();
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->bigInteger('documentable_id')->unsigned()->index();
            $table->string('documentable_type')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('number_generators', function (Blueprint $table) {
            $table->id();
            $table->string('generatable_type');
            $table->string('last_generated_value');
            $table->integer('length')->default(6);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_types');
        Schema::dropIfExists('pay_periods');
        Schema::dropIfExists('financial_years');
    }
};
