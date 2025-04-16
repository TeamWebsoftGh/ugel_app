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
//        Schema::table('rooms', function (Blueprint $table) {
//            //
//            $table->string('block')->nullable();
//        });
//
//        Schema::table('reviews', function (Blueprint $table) {
//            //
//            $table->string('block')->nullable()->after('comment');
//        });
//
//        Schema::dropIfExists('amenitable');
//        Schema::create('amenitables', function (Blueprint $table) {
//            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
//            $table->unsignedBigInteger('amenitable_id');
//            $table->string('amenitable_type');
//            $this->empExtracted($table);
//        });

        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->string('rent_type')->nullable();
            $table->string('rent_duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            //
        });
    }
};
