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
        Schema::create('support_topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $this->empExtracted($table);
            $table->foreignId('team_id')->constrained('teams');
        });

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

            $table->unsignedBigInteger('priority_id')->nullable()->index();
            $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('cascade');

            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $this->empExtracted($table);

        });

        Schema::create('support_ticket_comments', function (Blueprint $table) {
            $table->id();
            $table->text('subject')->nullable();
            $table->text('message');

            $table->unsignedBigInteger('support_ticket_id')->index();
            $table->foreign('support_ticket_id')->references('id')->on('support_tickets');

            $table->bigInteger('user_id')->unsigned()->index();
            $this->empExtracted($table);
        });

        Schema::create('support_ticket_users', function (Blueprint $table) {
            $table->unsignedBigInteger('support_ticket_id')->index();
            $table->foreign('support_ticket_id')->references('id')->on('support_tickets');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::create('customer_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->string('status')->default("submitted");
            $table->string('message');
            $table->string('client_ip')->nullable();
            $table->string('client_agent')->nullable();
            $table->string('form_id')->unique();

            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $this->empExtracted($table);
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
