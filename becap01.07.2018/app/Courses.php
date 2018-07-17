<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Item;
use Image;

class Courses extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price', 
        'date_start_selection',
        'date_end_selection',
        'type',
        'period', 
        'icon', 
        'is_active',
        'training_schedule',
        'slug',      
        'whats_app_link',
        'faq', 
        'notification_day_number',
        'notification',
    ];

    protected $casts = [
        'training_schedule' => 'array'
    ];

    public static $coursStatuses = [ 
        'Не активен',
        'Активен'
    ];

    public function getCoursStatus($status_id)
    {
        return self::$coursStatuses[$status_id];
    }

    public function items() {
    	return $this->hasMany(Item::class, 'course_id');
    }


    public function courseItems() {
        return $this->belongsToMany(Item::class);
    }

    public static function saveIcon( $image ){
        $filename  = time() . '.' . $image->getClientOriginalExtension();
        $img = Image::make($image->getRealPath());        
        $img->resize(null, 100, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save( public_path('uploads/icons/' . $filename), 80);        

        return $filename;
    }
   
}
