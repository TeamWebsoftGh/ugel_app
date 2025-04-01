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
        Schema::create('team_users', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->index();
            $table->foreign('team_id')->references('id')->on('teams');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('team_lead_id')->nullable()->index();
        });

        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->foreignId('property_unit_id')->nullable();
            $table->foreignId('maintenance_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team', function (Blueprint $table) {
            //
        });
    }
};
