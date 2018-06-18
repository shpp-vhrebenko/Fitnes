<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

  /*
    public function subcategories()
    {
        return $this->hasMany(ItemCategory::class, 'parent_id', 'id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_multi_categories','category_id','item_id');
    }
     */
   
}
