<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests\StoreResultRequest;

use App\Repositories\CategoriesRepositoryInterface;
use App\Repositories\ItemsRepositoryInterface;
use App\Repositories\ResultsRepositoryInterface;
use App\Repositories\UsersRepositoryInterface;
use App\Repositories\CoursesRepositoryInterface;

use Auth;
use Lang;

use Carbon\Carbon;

use App\Category;
use App\Item;
use App\Settings;
use App\Result;
use App\User;
use App\Courses;

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
        ResultsRepositoryInterface              $resultsRepository,
        UsersRepositoryInterface                $usersRepository,
        CoursesRepositoryInterface              $coursesRepository
    )
    {
        $this->items = $itemsRepository;              
        $this->categories = $categoriesRepository;
        $this->results = $resultsRepository;
        $this->users = $usersRepository;
        $this->courses = $coursesRepository;
        $currentUser = Auth::user();    
        if(Auth::user()) {
            if(Auth::user()->course_id) {
                $categories = $this->categories->all();
            } else {
                $categories = null;
            }
        } else {
            $categories = null;
        }
              
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
        return redirect()->route('show_trainings');       
    }

  
    public function show_courses()
    {
        $courses = Courses::where('is_active', true)->get();
        $title = 'Курсы';
        $description = 'У Вас закончился абонемент на текущий Курс. Вам необходимо оплатить  новый Курс или Марафон.';
        return view('my_acount/pages/courses/courses', compact(['title', 'description', 'courses']));
    }

    public function show_trainings()
    {        
        $currentUser = Auth::user(); 
        $course_id = $currentUser->course_id;     
        $course = $this->courses->find($course_id);        
        $training_schedule = $course->training_schedule;
        $currentDate = Carbon::now();       
        $dataStartCourse = Carbon::createFromFormat('Y-m-d H:i:s', $currentUser->data_start_course);
        $diffDays = $dataStartCourse->diffInDays($currentDate, false);    
        

        if($course->type == 'marathon') {
            $dataStartSelect = Carbon::createFromFormat('Y-m-d H:i:s', $course->date_start_selection);
            $diffMinutesStartCourse = $currentDate->diffInMinutes($dataStartCourse, false);      
            $diffMinutesStartSelect = $currentDate->diffInMinutes($dataStartSelect, false);

            if($diffMinutesStartSelect > 0 && $diffMinutesStartSelect > 1140) {
                $diffDaysStartSelectMarathon = $currentDate->diffInDays($dataStartSelect, false);
                $title = $course->name;
                $daysCount = Lang::choice('messages.days', $diffDaysStartSelectMarathon);
                $message = 'До начала отбора на марафон осталось '. $diffDaysStartSelectMarathon ." ".$daysCount ;
                
                return view('my_acount/pages/courses/marathon_message')->with(array('title' => $title, 'message' => $message, 'course' => $course)); 
            } elseif ($diffMinutesStartSelect > 0 && $diffMinutesStartSelect < 1140) {
                $diffHoursStartSelectMarathon = $currentDate->diffInHours($dataStartSelect, false);
                $title = $course->name;
                $hoursCount = Lang::choice('messages.hours', $diffHoursStartSelectMarathon);
                $message = 'До начала отбора на марафон осталось '. $diffHoursStartSelectMarathon." ".$hoursCount ;
                
                return view('my_acount/pages/courses/marathon_message')->with(array('title' => $title, 'message' => $message, 'course' => $course)); 
            } 

            if($diffMinutesStartCourse > 0 && $diffMinutesStartCourse > 1140) {
                $diffDaysStartMarathon = $currentDate->diffInDays($dataStartCourse, false); 
                $title = $course->name;
                $daysCount = Lang::choice('messages.days', $diffDaysStartMarathon);
                $message = 'До начала марафона сталось '. $diffDaysStartMarathon." ".$daysCount;
                return view('my_acount/pages/courses/marathon_message')->with(array('title' => $title, 'message' => $message, 'course' => $course));
            }  elseif ($diffMinutesStartCourse > 0 && $diffMinutesStartCourse < 1140) {
                $diffHoursStartMarathon = $currentDate->diffInHours($dataStartCourse, false);
                $title = $course->name;
                $hoursCount = Lang::choice('messages.hours', $diffHoursStartMarathon);
                $message = 'До начала марафона осталось '. $diffHoursStartMarathon." ".$hoursCount ;
                
                return view('my_acount/pages/courses/marathon_message')->with(array('title' => $title, 'message' => $message, 'course' => $course)); 
            }   
        }
             
        if($diffDays <= $course->period) {
            if($diffDays == 0) {
                $diffDays = 1;
            }
            $category = Category::where('id', 1)->firstOrFail(); 
            $items = $category->items()->where('course_id',$course_id)->get();

            $trainingItems = [];
            for ($i=0; $i < $diffDays; $i++) {
                $curDay = $i + 1;
                $curTraining = $training_schedule['day_'.$curDay];
                $curItem = null;
                foreach ($items as $key => $value) {
                    if($value['id'] == $curTraining['item_id']) {
                        $curItem = $items[$key];
                        $curItem['day'] = $curDay;
                    }
                }
               array_push( $trainingItems,$curItem);
            }        
            $title = 'Тренировки';
            $description = 'Выберите интересующий вас день тренировки';
            return view('my_acount/pages/trainings/trainings', compact(['trainingItems', 'title', 'description']));
        } else {
            $currentUser->update(['course_id' => 0]);
            return redirect()->route('courses_list');  
        }       

    }

    public function show_training($item_slug)
    {
        $item = $this->items->findWithParams(['slug' => $item_slug])->firstOrFail();
        $course = $this->courses->find($item->course_id);
        $training_schedule = $course->training_schedule; 
        $numberDay = null;
        foreach ($training_schedule as $key => $value) {
            if($value['item_id'] == $item->id) {
                $numberDay = str_replace('day_','',$key); 
            }
        }

        return view('my_acount/pages/trainings/trainings_item', compact(['item', 'numberDay']));
    }

    public function show_category_items($category_slug)
    {     
        $currentUser = Auth::user(); 
        $course_id = $currentUser->course_id;
        $category = Category::where('slug', $category_slug)->firstOrFail();       
        $items = $category->items()->where('course_id',$course_id)->get();

        $title = $category->name;
        $description = $category->description;
        return view('my_acount/pages/items/categories_items', compact(['category', 'items', 'title', 'description']));
    }

    public function show_item($category_slug, $item_slug)
    {        
        $item = $this->items->findWithParams(['slug' => $item_slug])->firstOrFail();        
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
        $curEmail = $request->get('email');          
        $currentUser = Auth::user();
        $results = $currentUser->results;
        $response = array(
            'status' => 'success',
            'results' => $results
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
        $curentUser = $this->users->find($result['user_id']);    

        $lastResult = $curentUser->results()->orderBy('id', 'desc')->first();  
        if(isset($lastResult->created_at)) {
            $lastResultDate = $lastResult->created_at;
            $currentDate = Carbon::now();   
            $diffHours = $lastResultDate->diffInHours($currentDate, false);

            if($diffHours < 24) {
                Session::flash('error', 'Отчет нильзя добавлять больше одного раза в 24 часа !');
                return redirect()->back(); 
            }
        }
        
        if($curentUser->status_id == 0) {
            $curentUser->update(['status_id' => 1]);
        }
        
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
