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
        Schema::table('support_tickets', function (Blueprint $table) {
            //
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('support_topic_id')->nullable()->index();
            $table->foreign('support_topic_id')->references('id')->on('support_topics')->onDelete('cascade');
        });

        Schema::table('customer_enquiries', function (Blueprint $table) {
            //
            $table->string('reply')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            //
        });
    }
};
