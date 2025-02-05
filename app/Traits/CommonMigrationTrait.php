<?php

namespace App\Traits;
use Illuminate\Database\Schema\Blueprint;

trait CommonMigrationTrait
{
    protected function empExtracted(Blueprint $table): void
    {
        $table->boolean('is_active')->default(1);
        $table->string('created_from', 100)->nullable();
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedBigInteger('import_id')->nullable();
        $table->unsignedBigInteger('company_id')->nullable()->index();
        $table->foreign('company_id')->references('id')->on('companies');

        $table->timestamps();
        $table->softDeletes();
    }
}
