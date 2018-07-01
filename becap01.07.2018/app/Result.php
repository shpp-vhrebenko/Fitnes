<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use User;
use Image;

class Result extends Model
{
    protected $fillable = [
        'image', 'weight', 'height', 'taliya', 'grud', 'bedra', 'user_id'
    ];

    public function user() {
    	return $this->hasOne(User::class);
    }

    public static function saveImage( $image ){
        $filename  = time() . '.' . $image->getClientOriginalExtension();
        $img = Image::make($image->getRealPath());        
        $img->resize(null, 300, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save( public_path('uploads/results/' . $filename), 60);        

        return $filename;
    }   
   
}
