<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Image;

class Settings extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $fillable = [
        'title',        
        'description',        
        'keywords',        
        'title_site',
        'owner',       
        'email',       
        'favicon',
        'logo'        
    ];

    public static function saveImage( $image, $type ){
        $filename  = time() . '.' . $image->getClientOriginalExtension();
        $img = Image::make($image->getRealPath());
        if( $type == 'logo' ) {
            $img->resize(null, 30, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save( public_path('uploads/logo/' . $filename), 45);
        }

        if( $type == 'favicon' ) {
            $filename  = time() . '-favicon-16x16' . $image->getClientOriginalExtension();
            $img->resize(16, 16);
            $img->save( public_path('uploads/favicon/'. $filename ), 45);
        }

        return $filename;
    }
}
