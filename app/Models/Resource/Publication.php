<?php

namespace App\Models\Resource;


use App\Abstracts\Model;
use App\Models\Auth\Team;
use App\Models\Property\Property;

class Publication extends Model
{
    //
    protected $fillable = ['file_path', 'title', 'is_active', 'slug', 'type', 'property_id', 'category_id', 'client_type_id','target_group'];

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault(['name' => 'All']);
    }

    public function team()
    {
        return $this->belongsTo(Team::class)->withDefault(["name" => "All"]);
    }

    public function property()
    {
        return $this->belongsTo(Property::class)->withDefault(["name" => "All"]);
    }


}
