<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'parent_id'
    ];

    public function getDescendantIds()
    {
        $ids = [];

        $ids = $this->hasMany(static::class, 'parent_id')->with('children')->pluck('id');
        return $ids->add($this->id);
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id')->withDefault(["name" => "N/A"]);
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function descendants()
    {
        return $this->hasMany(static::class, 'parent_id')->with('children');
    }


    public function getParentsNames() {

        $parents = collect([]);

        if($this->parent) {
            $parent = $this->parent;
            while(!is_null($parent)) {
                $parents->push($parent);
                $parent = $parent->parent;
            }
            return $parents;
        } else {
            return $this->name;
        }
    }

    public function getAllCategories() {

        $parents = collect([]);

        if($this->parent) {
            $parent = $this->parent;
            while(!is_null($parent)) {
                $parents->push($parent);
                $parent = $parent->parent;
            }
            return $parents;
        } else {
            return $this->name;
        }
    }
}
