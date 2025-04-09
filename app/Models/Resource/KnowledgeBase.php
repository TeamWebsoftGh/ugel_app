<?php

namespace App\Models\Resource;

use App\Abstracts\Model;
use App\Models\Common\DocumentUpload;

class KnowledgeBase extends Model
{
    protected $fillable = ['title', 'content', 'description', 'status', 'publish_date', 'category_id', 'slug'];

    public function getCategoryNameAttribute($value)
    {
        return $this->category->name??"Uncategorized";
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault(['name' => 'Uncategorized']);
    }

    public function documents()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

}
