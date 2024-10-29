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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('module')->default('task');
            $table->string('color')->nullable();
            $table->string('badge')->nullable();
            $table->string('icon')->nullable();
            $table->string('sort_order')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index();
            $table->string('colour', 50)->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('parent_task_id')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->text('remarks')->nullable();
            $table->text('resources')->nullable();
            $table->text('anticipated_challenges')->nullable();
            $table->text('anticipated_opportunities')->nullable();
            $table->boolean('is_ratable')->default(1);
            $table->boolean('has_budget')->default(0);
            $table->string('stage')->nullable();
            $table->boolean('is_accepted')->default(0);
            $table->boolean('budget_is_accepted')->default(0);
            $table->decimal('total_weightage')->nullable();
            $table->decimal('employee_score')->nullable();
            $table->decimal('budget')->nullable();
            $table->decimal('expense')->nullable();
            $table->decimal('revenue_target')->nullable();
            $table->decimal('actual_revenue')->nullable();

            $table->decimal('hourly_rate')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->date('submitted_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->UnsignedBiginteger('status_id')->nullable()->index();
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');

            $table->UnsignedBiginteger('priority_id')->nullable()->index();
            $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('cascade');

            $table->UnsignedBiginteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->UnsignedBiginteger('assignee_id')->nullable()->index();
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('approver_id')->nullable();
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();
        });

        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->text('subject')->nullable();
            $table->text('message');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('task_id')->index();
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->bigInteger('user_id')->unsigned()->index();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('check_list_items', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();
            $table->boolean('is_complete')->nullable();

            $table->unsignedBigInteger('task_id')->index();
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->bigInteger('user_id')->unsigned()->index();
            $table->dateTime('completed_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->text('title')->nullable();
            $table->text('challenges')->nullable();
            $table->text('note')->nullable();
            $table->text('comments')->nullable();
            $table->text('remarks')->nullable();
            $table->decimal('revenue')->nullable();
            $table->decimal('expense')->nullable();
            $table->time('duration')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('status')->default('completed');

            $table->unsignedBigInteger('task_id')->index();
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->unsignedBigInteger('check_list_item_id')->nullable();
            $table->foreign('check_list_item_id')->references('id')->on('check_list_items');

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
        Schema::dropIfExists('tasks');
    }
};
