<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

use App\Category;

use Image;

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

    public static function saveImage( $image ){
        $filename  = time() . '.' . $image->getClientOriginalExtension();
        $img = Image::make($image->getRealPath());        
        $img->heighten(300);
        $img->save( public_path('uploads/items/' . $filename) );        

        return $filename;
    }
}
