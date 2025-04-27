?"<?php


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
            $table->foreignId('team_id')->nullable()->constrained('teams');
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
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();

            $table->unsignedBigInteger('support_topic_id')->nullable()->index();
            $table->foreign('support_topic_id')->references('id')->on('support_topics')->onDelete('cascade');

            $table->unsignedBigInteger('priority_id')->nullable()->index();
            $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('cascade');

            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $this->empExtracted($table);

        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('message'); // Comment text

            // Polymorphic relationship
            $table->morphs('commentable'); // Creates `commentable_id` & `commentable_type`

            // User who made the comment
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Timestamps
            $table->timestamps();
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
            $table->text('reply')->nullable();$table->text('reply')->nullable();

            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $this->empExtracted($table);
        });

        Schema::create('maintenance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();

            // Self-referencing foreign key (nullable for main categories)
            $table->foreignId('parent_id')->nullable()->constrained('maintenance_categories')->onDelete('cascade');

            $this->empExtracted($table);
            $table->foreignId('team_id')->nullable()->constrained('teams');
        });


        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 15)->unique();
            $table->mediumText('description')->nullable();
            $table->mediumText('remarks')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->nullable();
            $table->string('client_number')->nullable();
            $table->string('client_phone_number')->nullable();
            $table->string('client_email')->nullable();
            $table->string('location')->nullable();
            $table->string('other_issue')->nullable();
            $table->boolean('is_notify')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->string('type')->nullable();
            $table->foreignId('property_unit_id')->nullable();
            $table->foreignId('maintenance_category_id')->nullable();
            $table->foreignId('priority_id')->nullable()->constrained('priorities')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade');
            $table->foreignId('property_id')->nullable();
            $table->foreignId('room_id')->nullable();

            $this->empExtracted($table);
        });

        // Many-to-Many Pivot Table
        Schema::create('maintenance_category_maintenance_requests', function (Blueprint $table) {
          //  $table->id();
            $table->foreignId('maintenance_id')->constrained('maintenance_requests')->onDelete('cascade');
          //  $table->string('other_issue')->nullable();
            // Shortened Foreign Key Name
            $table->foreignId('maintenance_category_id')
                ->constrained('maintenance_categories', 'id', 'fk_maint_category_request')
                ->onDelete('cascade');
        });

        Schema::create('maintenance_requests_users', function (Blueprint $table) {
            $table->unsignedBigInteger('maintenance_requests_id')->index();
            $table->foreign('maintenance_requests_id')->references('id')->on('maintenance_requests');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_category_maintenance');
        Schema::dropIfExists('maintenances');
        Schema::dropIfExists('maintenance_categories');
        Schema::dropIfExists('customer_enquiries');
        Schema::dropIfExists('customer_services');
    }
};
