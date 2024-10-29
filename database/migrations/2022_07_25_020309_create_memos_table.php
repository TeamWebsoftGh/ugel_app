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
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('send_email')->default(0);
            $this->extracted($table);
        });

        Schema::create('popup_builders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->string('only_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('offer_time_end')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('btn_status')->nullable();
            $table->text('description')->nullable();
            $this->extracted($table);
        });

        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('line_1')->nullable();
            $table->string('line_2')->nullable();
            $table->string('line_3')->nullable();
            $table->string('line_4')->nullable();
            $table->string('line_5')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->string('attachment')->nullable();
            $table->string('button_url')->nullable();
            $table->string('button_name')->nullable();
            $table->string('message_type')->nullable();
            $table->dateTime('request_date')->nullable();
            $table->boolean('is_sent')->default(0);

            $table->unsignedBigInteger('eloquentable_id')->nullable();
            $table->string('eloquentable_type')->index()->nullable();

            $table->unsignedBigInteger('emailable_id')->nullable();
            $table->string('emailable_type')->index()->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('sms_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('sender_id')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_sent')->default(0);

            $table->unsignedBigInteger('eloquentable_id')->nullable();
            $table->string('eloquentable_type')->index()->nullable();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

    /**
     * @param Blueprint $table
     * @return void
     */
    protected function extracted(Blueprint $table): void
    {
        $table->boolean('is_notify')->default(0)->nullable();
        $table->boolean('is_active')->default(1);

        $table->string('created_from', 100)->nullable();
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedBigInteger('import_id')->nullable();

        $table->unsignedBigInteger('company_id')->index();
        $table->foreign('company_id')->references('id')->on('companies');

        $table->timestamps();
        $table->softDeletes();
    }
};
