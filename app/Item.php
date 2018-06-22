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

    public static $ItemStatuses = [ 
        'Не активна',
        'Активна'
    ];

    public static $ItemTrainingStatuses = [ 
        'Не выходной',
        'Выходной'        
    ];

    public static $TrainingSettings = [
        'maxWeek' => 2,
        'maxDay' => 14,
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
