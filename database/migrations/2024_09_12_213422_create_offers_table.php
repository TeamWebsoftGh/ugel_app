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
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category')->nullable();
            $table->string('provider')->nullable();
            $table->string('cover')->nullable();
            $table->string('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->boolean('is_active')->default(1);
            $table->decimal('min_amount', 18, 2)->default(100.00);
            $table->decimal('max_amount', 18,2)->default(1000000.00);
            $table->boolean('built_in')->default(0);

            $table->bigInteger('parent_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('remarks')->nullable();
            $table->decimal('total_amount', 18, 2)->default(0.00);
            $table->decimal('total_paid', 18, 2)->default(0.00);
            $table->decimal('total_received', 18, 2)->default(0.00);
            $table->decimal('total_charges', 18, 2)->default(0.00);
            $table->boolean('is_active');
            $table->integer('sort_order')->default(0);
            $table->date('start_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->date('submitted_date')->nullable();
            $table->date('approved_date')->nullable();

            $table->string('status', 50)->default("Pending");
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->bigInteger('service_type_id')->unsigned()->index()->nullable();
            $table->foreign('service_type_id')->references('id')->on('service_types');
        });

        Schema::create('offer_comments', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('offer_id')->index();
            $table->foreign('offer_id')->references('id')->on('offers');

            $table->bigInteger('offer_commentable_id')->unsigned()->index();
            $table->string('offer_commentable_type')->index()->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
