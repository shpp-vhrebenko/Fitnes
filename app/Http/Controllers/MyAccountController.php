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
use Mail;


use Carbon\Carbon;

use App\Category;
use App\Item;
use App\Settings;
use App\Result;
use App\User;
use App\Courses;
use App\Order;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

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
        $categories = $this->categories->all();              
        $settings = Settings::first(); 
        $currentUser = Auth::user();
        if(isset($currentUser)) {
            dd($currentUser);
        }
             
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

    public function show_faq()
    {
        $currentUser = Auth::user(); 
        $course_id = $currentUser->course_id;     
        $course = $this->courses->find($course_id);        
        $faq = $course->faq;
        $title = "FAQ";
        return view('my_acount/pages/faq/faq', compact(['title', 'faq']));
    }

  
    public function show_courses()
    {
        $courses = Courses::where('is_active', true)->get();
        $title = 'Курсы';
        $description = 'Закончился текущий Курс. Вам необходимо оплатить  новый Курс или Марафон.';
        return view('my_acount/pages/courses/courses', compact(['title', 'description', 'courses']));
    }

    public function by_course(Request $request)
    {
        $slug = $request->get('course_slug');

        if(isset($slug) && $slug != NULL) {                       
            $course = Courses::where('slug', $slug)->first();  
            $currentUser = Auth::user();        

            $newUserOrder = Order::create([
                'user_id' => $currentUser->id,
                'course_id' => $course->id,
                'status_id' => 2, // В ожидании оплаты    
                'user_status' => 1, // Пользователь не зарегистрирован
                'total' => $course->price,                
            ]); 

            $request->session()->put('id_user_order', $newUserOrder->id);

            return redirect()->route('oplata');
        } else {
            Session::flash('error', 'Необходимо выбрать Курс или Марафон!');
            return redirect()->back();
        }
        
    }

    public function show_trainings()
    { 
        $notification = null;       
        $currentUser = Auth::user(); 
        $course_id = $currentUser->course_id;     
        $course = $this->courses->find($course_id);         
        $currentDayCourse = self::getCurrentCourseDayNumber($currentUser->data_start_course);
        $training_schedule = $course->training_schedule;
        
        

        if($course->type == 'marathon') {            
            $diffMinutesStartCourse = $currentDate->diffInMinutes($dataStartCourse, false); 

            /*$dataStartSelect = Carbon::createFromFormat('Y-m-d', $course->date_start_selection);
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
            } */

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
             
        if($currentDayCourse <= $course->period) {
            $diffPeriodCourse_currentDayCourse = $course->period - $currentDayCourse;            
            if($diffPeriodCourse_currentDayCourse == $course->notification_day_number) {
                $notification = $course->notification;
            }
            if($currentDayCourse == 0) {
                $currentDayCourse = 1;
            }
            $category = Category::where('id', 1)->firstOrFail(); 
            $items = $category->items()->where('course_id',$course_id)->get();

            $trainingItems = [];
            for ($i=0; $i < $currentDayCourse; $i++) {
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

            return view('my_acount/pages/trainings/trainings', compact(['trainingItems', 'title', 'description', 'notification']));
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
        $id_last_result = $currentUser->id_last_result;     
        return view('my_acount/pages/results/results', compact(['results', 'id_last_result']));
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
            $currentDate = Carbon::now();
            $userCourse = $this->courses->find($curentUser->course_id);
            if($userCourse->type == 'cours') {
                $curentUser->update(['data_start_course' => $currentDate]);
            }
        }
        
        if( $image = $request->file('result.image') )
        {            
            $result['image'] = Result::saveImage( $image );                 
        }

        $this->results->create($result);        

        Session::flash('success', 'Отчет успешно создан!');
        /*return redirect()->back(); */   
        return redirect()->route('show_trainings'); 
    }

    public function result_edit()
    {

    }

    public function result_update() 
    {

    }

     public function result_delete(Request $request) 
    {
        $result_id = $request->get('result_id');
        if(isset($result_id)) {
            $currentUser = Auth::user();
            $id_last_result = $currentUser->id_last_result;
            if($id_last_result == 0) {
                $currentResult = $this->results->find($result_id);                
                if( $currentResult->image != 'no-image.png')
                {
                    File::delete( public_path('uploads/results/'. $currentResult->image ));                   
                }                 
                $currentResult->delete();
                $currentUser->update(['id_last_result' => $result_id]);
                return redirect()->route('show_results'); 
            } else if ($id_last_result > 0 && $id_last_result < $result_id) {
                $currentResult = $this->results->find($result_id);                
                if( $currentResult->image != 'no-image.png')
                {
                    File::delete( public_path('uploads/results/'. $currentResult->image ));                   
                }  
                $currentResult->delete();
                $currentUser->update(['id_last_result' => $result_id]);
                return redirect()->route('show_results'); 
            }
                    
        }
        return redirect()->route('show_results');        
    }

    protected static function  getCurrentCourseDayNumber($data_start_course)
    {
        $currentDate = Carbon::now();       
        $dataStartCourse = Carbon::createFromFormat('Y-m-d H:i:s', $data_start_course);
        return $dataStartCourse->diffInDays($currentDate, false); 
    }
}
