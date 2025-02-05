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
        Schema::create('contact_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $this->empExtracted($table);
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('surname')->nullable();
            $table->string('other_names')->nullable();
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->string('phone_number');
            $table->date('date_of_birth')->nullable();

            $table->unsignedBigInteger('contact_group_id')->index();
            $table->foreign('contact_group_id')->references('id')->on('contact_groups');
            $this->empExtracted($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('contact_groups');
    }
};
