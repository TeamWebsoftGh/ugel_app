<?php

namespace App\Models\Resource;

use App\Models\Common\DocumentUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'description', 'status', 'publish_date', 'category_id', 'slug'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format')." - h:m a");
    }

    public function getCategoryNameAttribute($value)
    {
        return $this->category->name??"Uncategorized";
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function documents()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

}
