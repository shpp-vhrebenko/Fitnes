<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\CategoriesRepositoryInterface;
use App\Repositories\ItemsRepositoryInterface;

use App\Category;
use App\Item;
use App\Settings;

class MyAccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct
    (
        ItemsRepositoryInterface                    $itemsRepository,        
        CategoriesRepositoryInterface           $categoriesRepository  
    )
    {
        $this->items = $itemsRepository;              
        $this->categories = $categoriesRepository;
        $categories = $this->categories->all();
        $settings = Settings::first();      
        view()->share(compact([ 'settings', 'categories']));        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('my_acount.home');
    }

    public function show_category_items($category_slug)
    {
        
        /*$category = $this->categories->findWithParams('id', 1)->get();  */
        $category = Category::where('slug', $category_slug)->firstOrFail();       
        $items = $category->items;
        $title = $category->name;
        $description = $category->description;
        return view('my_acount/pages/items/categories_items', compact(['category', 'items', 'title', 'description']));
    }

    public function show_item($category_slug, $item_id)
    {        
        $item = $this->items->find($item_id);        
        return view('my_acount/pages/items/item', compact([ 'item']));
    }
}
