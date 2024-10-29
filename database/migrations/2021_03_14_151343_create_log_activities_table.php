<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class
CreateLogActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('log_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('slug');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->bigInteger('log_type_id')->unsigned()->index();
            $table->foreign('log_type_id')->references('id')->on('log_types');
        });

        Schema::create('log_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->bigInteger('subject_id')->unsigned()->index()->nullable();
            $table->string('subject_type')->index()->nullable();
            $table->text('before')->nullable();
            $table->text('after')->nullable();
            $table->string('client_ip')->nullable();
            $table->string('request_url')->nullable();
            $table->string('client_agent')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->string('user_model')->nullable();

            $table->bigInteger('log_action_id')->unsigned()->index()->nullable();
            $table->foreign('log_action_id')->references('id')->on('log_actions');

            $table->bigInteger('log_type_id')->unsigned()->index()->nullable();
            $table->foreign('log_type_id')->references('id')->on('log_types');

            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('error_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumText('message');
            $table->string('file')->nullable();
            $table->string('line')->nullable();
            $table->string('error_code')->nullable();
            $table->bigInteger('subject_id')->unsigned()->index()->nullable();
            $table->string('subject_type')->index()->nullable();
            $table->string('client_ip')->nullable();
            $table->string('request_url')->nullable();
            $table->string('client_agent')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->string('user_model')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('company_id')->nullable();

            $table->unsignedBigInteger('log_action_id')->index()->nullable();
            $table->foreign('log_action_id')->references('id')->on('log_actions');

            $table->bigInteger('log_type_id')->unsigned()->index()->nullable();
            $table->foreign('log_type_id')->references('id')->on('log_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_activities');
    }
}
