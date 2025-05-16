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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('user_type')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('team_lead_id')->nullable()->index();

            $this->empExtracted($table);
        });


        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('other_names')->nullable();
            $table->string('username',100)->unique();
            $table->string('email',100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('dark_mode')->default(1);
            $table->string('password');
            $table->string('profile_photo')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('phone_number',15)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('last_login_ip',32)->nullable();
            $table->timestampTz('last_login_date','2')->nullable();
            $table->boolean('ask_password_reset')->default(0);
            $table->date('last_password_reset')->nullable();
            $table->timestamp('last_active')->nullable();
            $table->rememberToken();
            $table->string('session_id')->nullable();

            $table->boolean('is_builtin')->default(0);
            $table->unsignedBigInteger('client_id')->nullable();

            $this->empExtracted($table);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('medium')->nullable();
            $table->string('otp');
            $table->timestamp('expire_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('device')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('team_users', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->index();
            $table->foreign('team_id')->references('id')->on('teams');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    protected function empExtracted(Blueprint $table): void
    {
        $table->string('created_from', 100)->nullable();
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedBigInteger('import_id')->nullable();

        $table->unsignedBigInteger('company_id')->nullable()->index();
        $table->foreign('company_id')->references('id')->on('companies');

        $table->timestamps();
        $table->softDeletes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
