<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

use App\Category;

class Item extends Model
{
    protected $fillable = [
                'title',
                'image',
                'text',
                'category_id',
                'course_id',
                'is_active'
            ];

    public $statuses = ['Не активна', 'Активена'];

    public static function get_statuses()
    {
        $item = new Item();
        return $item->statuses;
    }    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
