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
        Schema::dropIfExists('court_cases');
        Schema::create('court_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->string('title');
            $table->string('type'); // Civil, Criminal, etc.
            $table->string('category')->nullable(); // e.g. Land Dispute, Divorce
            $table->string('court_name')->nullable();
            $table->string('always_cc')->nullable();
            $table->string('status')->default('Pending');
            $table->foreignId('lawyer_id')->nullable();
            $table->foreignId('priority_id')->nullable()->constrained('priorities')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->date('filed_at')->nullable();
            $table->date('closed_at')->nullable();
            $this->empExtracted($table);
        });

        Schema::dropIfExists('court_hearings');
        Schema::create('court_hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_case_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->string('venue')->nullable();
            $table->string('judge')->nullable();
            $table->text('outcome')->nullable();
            $table->text('notes')->nullable();
            $this->empExtracted($table);
        });

        Schema::dropIfExists('offence_types');
        Schema::create('offence_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $this->empExtracted($table);
        });

        Schema::dropIfExists('offenses');
        Schema::create('offenses', function (Blueprint $table) {
            $table->id();
            $table->mediumText('description')->nullable();
            $table->date('offence_date');
            $table->date('warning_date')->nullable();
            $table->boolean('query_given')->default(0);
            $table->boolean('query_responded')->default(0);
            $table->boolean('investigation_required')->default(0);
            $table->boolean('investigation_held')->default(0);
            $table->decimal('percentage_pay')->default(100.00);
            $table->string('status',40)->default("pending");
            $table->longText('investigation_report')->nullable();
            $table->dateTime('action_start_date')->nullable();
            $table->dateTime('action_end_date')->nullable();

            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->unsignedBigInteger('warning_type_id')->nullable();
            $table->unsignedBigInteger('offence_type_id')->index();
            $this->empExtracted($table);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_cases');
    }
};
