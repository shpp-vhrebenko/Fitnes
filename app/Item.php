<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

use App\Category;
use App\Courses;
use App\User;

use Image;

class Item extends Model
{
    protected $fillable = [
                'title',
                'image',
                'text',
                'category_id',
                'course_id',
                'is_active',
                'is_holiday',
                'number_day',
                'slug'
            ];    

    public static $ItemStatuses = [ 
        'Не активна',
        'Активна'
    ];

    public static $ItemTrainingStatuses = [ 
        'Не выходной',
        'Выходной'        
    ];    

    public function getItemTrainingStatus($status_id)
    {
        return self::$ItemTrainingStatuses[$status_id];
    } 

    public function getItemStatus($status_id)
    {
        return self::$ItemStatuses[$status_id];
    } 

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'item_user', 'item_id', 'user_id')
        ->withPivot('is_done')
        ->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'course_item', 'item_id', 'course_id');
    }

    public function coursesId($course_id){
        
        return $this->belongsToMany(Courses::class, 'course_item', 'item_id', 'course_id')->wherePivot('course_id', $course_id);
    }

    public static function saveImage( $image ){
        $filename  = time() . '.' . $image->getClientOriginalExtension();
        $img = Image::make($image->getRealPath());        
        $img->resize(null, 300, function ($constraint) {
            $constraint->aspectRatio();
        });      
        $img->save( public_path('uploads/items/' . $filename), 45);      

        return $filename;
    }
  
}
