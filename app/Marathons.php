<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Item;
use Image;

class Marathons extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price', 
        'period', 
        'icon', 
        'is_active',
        'date_start_selection',
        'date_end_selection'      
    ];

    public static $marathonStatuses = [ 
        'Не активен',
        'Активен'
    ];

    public function getMarathonStatus($status_id)
    {
        return self::$marathonStatuses[$status_id];
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
