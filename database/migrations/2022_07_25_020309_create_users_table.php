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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('other_names')->nullable();
            $table->string('username',100)->unique();
            $table->string('email',100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('type')->default(2);
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

            $table->boolean('is_builtin')->default(0);

            $this->empExtracted($table);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('users');
    }

    protected function empExtracted(Blueprint $table): void
    {
        $table->string('created_from', 100)->nullable();
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedBigInteger('import_id')->nullable();

        $table->unsignedBigInteger('company_id')->index();
        $table->foreign('company_id')->references('id')->on('companies');

        $table->timestamps();
        $table->softDeletes();
    }
};
