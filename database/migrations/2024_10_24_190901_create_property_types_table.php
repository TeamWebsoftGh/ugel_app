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
        Schema::create('property_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('property_category_id')->constrained('property_categories');
        });

        Schema::create('unit_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('amenitable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('amenitable_id');
            $table->string('amenitable_type');
            $table->timestamps();
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_code')->unique();
            $table->string('property_name')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->integer('number_of_units')->nullable();

            $table->string('status')->nullable();
            $table->boolean('is_active')->default(1);
            $table->text('description')->nullable();

            $table->string('created_by', 36)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('property_type_id')->constrained('property_types');
        });

        Schema::create('property_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('lease_amount')->default(0)->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('address')->nullable();
            $table->mediumText('map_link')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->string('agent_commission_value')->nullable();
            $table->string('agent_commission_type')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->bigInteger('property_id')->unsigned()->index();
            $table->foreign('property_id')->references('id')->on('properties');
        });

        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name');
            $table->integer('bedroom')->nullable();
            $table->integer('bathroom')->nullable();
            $table->integer('kitchen')->nullable();
            $table->integer('total_rooms')->nullable();
            $table->decimal('general_rent')->default(0)->nullable();
            $table->decimal('security_deposit')->default(0)->nullable();
            $table->decimal('late_fee')->default(0)->nullable();
            $table->decimal('incident_receipt')->default(0)->nullable();
            $table->tinyInteger('rent_type')->comment('1=monthly,2=yearly,3=custom')->nullable();
            $table->integer('monthly_due_day')->nullable();
            $table->integer('yearly_due_day')->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->date('lease_payment_due_date')->nullable();
            $table->tinyText('description')->nullable();
            $table->string('square_feet')->nullable();
            $table->string('amenities')->nullable();
            $table->string('parking')->nullable();
            $table->string('condition')->nullable();

            $table->string('status')->nullable();
            $table->boolean('is_active')->default(1);

            $table->double('rent_amount')->nullable();
            $table->integer('unit_floor')->nullable();
            $table->string('square_foot')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->bigInteger('property_id')->unsigned()->index();
            $table->foreign('property_id')->references('id')->on('properties');
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name');
            $table->integer('floor')->nullable();
            $table->integer('bed_count')->default(1)->comment('Number of beds in the room');
            $table->foreignId('property_unit_id')->constrained('property_units');
            $table->string('status')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_types');
    }
};
