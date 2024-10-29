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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code',15)->unique();
            $table->string('subject');
            $table->mediumText('description')->nullable();
            $table->mediumText('remarks')->nullable();
            $table->string('ticket_note')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_notify')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->UnsignedBiginteger('priority_id')->nullable()->index();
            $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('cascade');

            $table->UnsignedBiginteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

        });

        Schema::create('support_ticket_comments', function (Blueprint $table) {
            $table->id();
            $table->text('subject')->nullable();
            $table->text('message');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('support_ticket_id')->index();
            $table->foreign('support_ticket_id')->references('id')->on('support_tickets');

            $table->bigInteger('user_id')->unsigned()->index();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

        });

        Schema::create('support_ticket_users', function (Blueprint $table) {
            $table->unsignedBigInteger('support_ticket_id')->index();
            $table->foreign('support_ticket_id')->references('id')->on('support_tickets');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

        });

        Schema::create('customer_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->string('message');
            $table->string('client_ip')->nullable();
            $table->string('client_agent')->nullable();
            $table->string('form_id')->unique();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_services');
    }
};
