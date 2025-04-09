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
        Schema::table('publications', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('property_type_id')->nullable();
            $table->foreign('property_type_id')->references('id')->on('property_types');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->foreign('property_id')->references('id')->on('properties');
            $table->unsignedBigInteger('client_type_id')->nullable();
            $table->foreign('client_type_id')->references('id')->on('client_types');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->string('target_group')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            //
        });
    }
};
