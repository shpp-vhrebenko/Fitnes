<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests\StoreResultRequest;

use App\Repositories\CategoriesRepositoryInterface;
use App\Repositories\ItemsRepositoryInterface;
use App\Repositories\ResultsRepositoryInterface;

use Auth;

use App\Category;
use App\Item;
use App\Settings;
use App\Result;

use Illuminate\Support\Facades\Session;

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
        CategoriesRepositoryInterface           $categoriesRepository,
        ResultsRepositoryInterface              $resultsRepository  
    )
    {
        $this->items = $itemsRepository;              
        $this->categories = $categoriesRepository;
        $this->results = $resultsRepository;
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

    public function show_results(Request $request)
    {   
        $currentUser = Auth::user();
        $results = $currentUser->results;       
        return view('my_acount/pages/results/results', compact(['results']));
    }   

    public function get_results(Request $request)
    {   
                   
        $currentUser = Auth::user();
        $results = $currentUser->results;
        $response = array(
            'status' => 'success',
            'results' => $results,
        );
        return response()->json($response);            
                
    }   


    public function add_result()
    {
        return view('my_acount/pages/results/new_result');
    }

    public function result_store(StoreResultRequest $request)
    {        
        if(!Auth::check()) {
            return redirect('/login');
        }
        $result = $request->get('result');        
        $result['user_id'] = Auth::id();     
        
        if( $image = $request->file('result.image') )
        {            
            $result['image'] = Result::saveImage( $image );                 
        }

        $this->results->create($result);        

        Session::flash('success', 'Отчет успешно создан!');
        return redirect()->back();    
    }

    public function result_edit()
    {

    }

    public function result_update() 
    {

    }
}
