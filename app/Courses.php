<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Item;
use Image;

class Courses extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price', 
        'period', 
        'icon', 
        'is_active'       
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
    	return $this->hasMany(Item::class);
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
