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
        Schema::dropIfExists('workflow_request_details');
        Schema::dropIfExists('workflow_requests');
        Schema::dropIfExists('workflow_positions');
        Schema::dropIfExists('workflow_position_types');
        Schema::dropIfExists('workflows');
        Schema::dropIfExists('workflow_types');
        Schema::create('workflow_position_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_workflow_only')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('workflow_positions', function (Blueprint $table) {
            $table->id();
            $table->string('position_name');
            $table->string('short_name')->nullable();
            $table->string('subject_type')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('reports_to')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->UnsignedBiginteger('workflow_position_type_id')->index();
            $table->foreign('workflow_position_type_id')->references('id')->on('workflow_position_types')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('workflow_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('subject_type')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('type')->nullable();
            $table->integer('stages')->default(1);
            $table->boolean('is_active')->default(1);
            $table->text('approval_route')->nullable();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('workflow_name')->nullable();
            $table->string('action_type')->default("approve");
            $table->text('description')->nullable();
            $table->mediumText('always_copy')->nullable();
            $table->mediumText('submit_message')->nullable();
            $table->mediumText('approve_message')->nullable();
            $table->mediumText('decline_message')->nullable();
            $table->mediumText('forward_message')->nullable();
            $table->mediumText('inform_message')->nullable();
            $table->integer('flow_sequence')->default(1);
            $table->boolean('can_update')->default(0);
            $table->boolean('send_email')->default(1);
            $table->boolean('send_sms')->default(0);
            $table->boolean('requires_comment')->default(0);
            $table->boolean('is_active')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->unsignedBigInteger('return_to')->nullable();

            $table->UnsignedBiginteger('workflow_position_type_id')->index();
            $table->foreign('workflow_position_type_id')->references('id')->on('workflow_position_types')->onDelete('cascade');

            $table->unsignedBigInteger('workflow_type_id');
            $table->foreign('workflow_type_id')->references('id')->on('workflow_types');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('workflow_requests', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('action_type')->default('approve_for');
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_active')->default(1);
            $table->integer('current_flow_sequence')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->UnsignedBiginteger('workflow_id')->nullable()->index();
            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');

            $table->UnsignedBiginteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->UnsignedBiginteger('property_id')->nullable();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            $table->UnsignedBiginteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('workflow_requestable_id')->unsigned()->index();
            $table->string('workflow_requestable_type')->index()->nullable();

            $table->unsignedBigInteger('workflow_type_id');
            $table->foreign('workflow_type_id')->references('id')->on('workflow_types');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('workflow_request_details', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('action_status')->nullable();
            $table->boolean('is_active')->default(1);
            $table->text('approver_comment')->nullable();
            $table->text('requestor_comment')->nullable();
            $table->text('approval_route')->nullable();
            $table->integer('flow_sequence')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('forwarded_at')->nullable();
            $table->unsignedBigInteger('old_implementor_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->UnsignedBiginteger('workflow_position_type_id')->index();
            $table->foreign('workflow_position_type_id')->references('id')->on('workflow_position_types')->onDelete('cascade');

            $table->UnsignedBiginteger('implementor_id')->index();
            $table->foreign('implementor_id')->references('id')->on('users')->onDelete('cascade');

            $table->UnsignedBiginteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('workflow_request_id');
            $table->foreign('workflow_request_id')->references('id')->on('workflow_requests');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedBigInteger('workflow_type_id')->nullable();
            $table->foreign('workflow_type_id')->references('id')->on('workflow_types');

            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->foreign('workflow_id')->references('id')->on('workflows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_request_details');
        Schema::dropIfExists('workflow_requests');
        Schema::dropIfExists('workflow_position_types');
        Schema::dropIfExists('workflow_positions');
        Schema::dropIfExists('workflow_types');
        Schema::dropIfExists('workflows');
    }
};
