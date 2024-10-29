<?php

use App\Models\Resource\Category;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(0);
            $table->string('cover_image')->nullable();
            $table->string('type')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('parent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();
        });

        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('colour_tag')->nullable();
            $table->string('target_group')->nullable();
            $table->unsignedBigInteger('target_group_id')->nullable();
            $table->text('description')->nullable();
            $table->date('publish_date')->nullable();
            $table->longText('content')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('slug')->unique();
            $table->string('views')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();

        });

        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('type')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('notify')->default(0);
            $table->boolean('is_active')->default(1);
            $table->string('slug', 191)->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedBigInteger('import_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
