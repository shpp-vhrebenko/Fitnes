<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

  /*  public function locales()
    {
        return $this->hasMany(ItemCategoriesTranslation::class, 'category_id', 'id');
    }

    public function subcategories()
    {
        return $this->hasMany(ItemCategory::class, 'parent_id', 'id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_multi_categories','category_id','item_id');
    }

    public function preview()
    {
        return $this->hasOne(Image::class, 'id', 'preview_id');
    }

    public function poster()
    {
        return $this->hasOne(Image::class, 'id', 'poster_id');
    }*/
}
