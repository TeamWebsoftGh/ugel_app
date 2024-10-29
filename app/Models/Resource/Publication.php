<?php

namespace App\Models\Resource;


use App\Abstracts\Model;

class Publication extends Model
{
    //
    protected $fillable = ['file_path', 'title', 'is_active', 'slug', 'type', 'department_id', 'category_id', 'subsidiary_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
