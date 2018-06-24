<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Settings;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){    	
        
    }

    public function setTitle($title)
    {
        view()->share('title', $title);
    }

    public function setTitleFront($meta_title, $title)
    {
        if (isset($meta_title))
        {
            view()->share('meta_title', strip_tags($meta_title));
        } else {
            view()->share('meta_title', strip_tags($title));
        }
        view()->share('title', strip_tags($title));
    }

    public function setDescription($description)
    {
        view()->share('meta_description', $description);
    }

    public function setKeywords($keywords)
    {
        view()->share('meta_keywords', $keywords);
    }


   
}
