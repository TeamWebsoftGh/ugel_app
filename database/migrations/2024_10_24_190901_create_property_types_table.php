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
        Schema::create('property_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('short_name')->nullable();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();

            $this->empExtracted($table);
        });

        Schema::create('property_purposes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();

            $this->empExtracted($table);
        });

        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $this->empExtracted($table);

            $table->foreignId('property_category_id')->constrained('property_categories');
        });

        Schema::create('unit_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();

            $this->empExtracted($table);
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $this->empExtracted($table);
        });

        Schema::create('amenitable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('amenitable_id');
            $table->string('amenitable_type');
            $this->empExtracted($table);
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_code')->unique();
            $table->string('property_name')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->integer('number_of_units')->nullable();

            $table->string('status')->nullable();
            $table->text('description')->nullable();

            $table->text('physical_address')->nullable();
            $table->text('digital_address')->nullable();
            $table->text('house_number')->nullable();

            $table->mediumText('map_link')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('agent_commission_value')->nullable();
            $table->string('agent_commission_type')->nullable();
            $table->decimal('lease_amount')->default(0)->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $this->empExtracted($table);
            $table->foreignId('property_type_id')->constrained('property_types');
            $table->foreignId('property_purpose_id')->constrained('property_purposes');
            $table->foreignId('city_id')->constrained('cities');
        });

        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name');
            $table->integer('total_bedroom')->nullable();
            $table->integer('total_bathroom')->nullable();
            $table->integer('total_kitchen')->nullable();
            $table->integer('total_rooms')->nullable();
            $table->integer('garage')->nullable();
            $table->decimal('total_area')->nullable();
            $table->decimal('general_rent')->default(0)->nullable();
            $table->string('deposit_type')->nullable();
            $table->decimal('security_deposit')->default(0)->nullable();
            $table->string('late_fee_type')->nullable();
            $table->decimal('late_fee')->default(0)->nullable();
            $table->decimal('incident_receipt')->default(0)->nullable();
            $table->string('rent_type')->comment('1=daily,2=monthly,3=yearly,4=custom')->nullable();
            $table->integer('monthly_due_day')->nullable();
            $table->integer('yearly_due_day')->nullable();
            $table->integer('rent_duration')->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->date('lease_payment_due_date')->nullable();
            $table->tinyText('description')->nullable();
            $table->string('square_feet')->nullable();
            $table->string('amenities')->nullable();
            $table->string('parking')->nullable();
            $table->string('condition')->nullable();

            $table->string('status')->nullable();

            $table->decimal('rent_amount')->nullable();
            $table->integer('unit_floor')->nullable();
            $this->empExtracted($table);

            $table->bigInteger('property_id')->unsigned()->index();
            $table->foreign('property_id')->references('id')->on('properties');
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name');
            $table->integer('floor')->nullable();
            $table->boolean('has_ac')->default(0);
            $table->boolean('has_washroom')->default(0);
            $table->integer('bed_count')->default(1)->comment('Number of beds in the room');
            $table->foreignId('property_unit_id')->constrained('property_units');
            $table->string('status')->nullable();
            $this->empExtracted($table);
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
