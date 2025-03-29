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
        Schema::create('booking_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->date('booking_start_date')->nullable();
            $table->date('booking_end_date')->nullable();
            $table->date('extension_date')->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $this->empExtracted($table);
        });

        Schema::create('property_unit_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_period_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 18, 2);
            $table->string('rent_type')->comment('1=daily,2=monthly,3=yearly,4=Per-sem,5=custom')->nullable();
            $this->empExtracted($table);
        });


        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('booking_period_id')->constrained()->onDelete('cascade');
            $table->string('booking_type');
            $table->date('lease_start_date');
            $table->date('lease_end_date');
            $table->date('extension_date')->nullable();
            $table->decimal('total_price', 18, 2);
            $table->decimal('sub_total', 18, 2);
            $table->decimal('total_paid', 18, 2);
            $table->string('status')->default('pending');
            $this->empExtracted($table);
        });

        Schema::create('invoice_item_lookups', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // e.g., Cleaning Fee, Maintenance Fee, etc.
            $table->decimal('price', 18, 2);      // Fixed price for the item
            $table->string('description')->nullable();
            $this->empExtracted($table);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 18, 2);
            $table->decimal('sub_total_amount', 18, 2);
            $table->string('status')->default('pending');
            $this->empExtracted($table);
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_item_lookup_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $this->empExtracted($table);
        });
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('comment')->nullable();
            $table->integer('rating')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $this->empExtracted($table);

            $table->unsignedBigInteger('property_id')->index();
            $table->foreign('property_id')->references('id')->on('properties');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
