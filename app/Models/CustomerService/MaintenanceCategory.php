<?php

namespace App\Models\CustomerService;


use App\Abstracts\Model;

class MaintenanceCategory extends Model
{
    protected $tenantable = false;
    protected $fillable = ['name', 'short_name', 'description', 'parent_id', 'team_id'];

    /**
     * Relationship: Parent Category
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MaintenanceCategory::class, 'parent_id');
    }

    /**
     * Relationship: Subcategories
     */
    public function subcategories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MaintenanceCategory::class, 'parent_id');
    }
}
