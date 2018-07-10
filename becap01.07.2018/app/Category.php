<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Item;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    public static $CategoryStatuses = [ 
        'Не активна',
        'Активна'
    ];

    public function getCategoryStatus($status_id)
    {
        return self::$CategoryStatuses[$status_id];
    } 
  

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    
   
}
