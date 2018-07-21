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
use App\Motivations;

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
             
        view()->share(compact([ 'settings', 'categories']));        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $this->setTitle('Личный кабинет - тренировки');    
        return redirect()->route('show_trainings');       
    }

    public function show_faq()
    {
        $currentUser = Auth::user(); 
        $course_id = $currentUser->course_id;     
        $course = $this->courses->find($course_id);        
        $faq = $course->faq;
        $this->setTitle($course->name . ' - FAQ'); 
        $page_title = "FAQ";
        return view('my_acount/pages/faq/faq', compact(['page_title', 'faq']));
    }

  
    public function show_courses()
    {
        $courses = Courses::where('is_active', true)->get();
        $this->setTitle('Личный кабинет - список курсов'); 
        $page_title = 'Курсы';
        $description = 'Закончился текущий Курс. Вам необходимо оплатить  новый Курс или Марафон.';
        return view('my_acount/pages/courses/courses', compact(['page_title', 'description', 'courses']));
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
                'user_status' => 1, // Пользователь зарегистрирован
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
        $currentUser = Auth::user(); 
        $course_id = $currentUser->course_id;     
        $course = $this->courses->find($course_id);         
        $currentDayCourse = self::getCurrentCourseDayNumber($currentUser->data_start_course);
        $training_schedule = $course->training_schedule;
        $currentDate = Carbon::now(); 
        $data_start_course = $currentUser->data_start_course;
        $dataStartCourse = Carbon::createFromFormat('Y-m-d H:i:s', $data_start_course);        

        // How many days left before the start of the marathon
        if($course->type == 'marathon') {  
            $diffMinutesStartCourse = $currentDate->diffInMinutes($dataStartCourse, false);

            if($diffMinutesStartCourse > 0 && $diffMinutesStartCourse > 1140) {
                $diffDaysStartMarathon = $currentDate->diffInDays($dataStartCourse, false); 
                $page_title = $course->name;
                $daysCount = Lang::choice('messages.days', $diffDaysStartMarathon);
                $message = 'До начала марафона сталось '. $diffDaysStartMarathon." ".$daysCount;
                $this->setTitle($course->name . ' - Тренировки'); 
                return view('my_acount/pages/courses/marathon_message')->with(array('page_title' => $title, 'message' => $message, 'course' => $course));
            }  elseif ($diffMinutesStartCourse > 0 && $diffMinutesStartCourse < 1140) {
                $diffHoursStartMarathon = $currentDate->diffInHours($dataStartCourse, false);
                $page_title = $course->name;
                $hoursCount = Lang::choice('messages.hours', $diffHoursStartMarathon);
                $message = 'До начала марафона осталось '. $diffHoursStartMarathon." ".$hoursCount ;
                $this->setTitle($course->name); 
                return view('my_acount/pages/courses/marathon_message', compact(['page_title','message', 'course'])); 
            }   
        }
             
        // Format training shedule for current course. If the marathon or course has already begun
        if($currentDayCourse <= $course->period) {
            $notification = array();
            $notification['message'] = self::isShowNotification($currentUser, $course, $currentDayCourse);            
           
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
                        $curItem['course_slug'] = $course->slug;
                    }
                }
               array_push( $trainingItems,$curItem);
            }        
            $page_title = 'Тренировки';
            $description = 'Выберите интересующий вас день тренировки';
            $this->setTitle($course->name . ' - Тренировки');  
            return view('my_acount/pages/trainings/trainings', compact(['trainingItems', 'page_title', 'description', 'notification']));
        } else {
            $currentUser->update([
                'course_id' => 0,
                'data_start_course' => NULL,
                'check_notification' => 0,
                ]);           
            return redirect()->route('courses_list');  
        }       

    }

    public function check_user_notification(Request $request) {                 
        $currentUser = Auth::user();
        $results = $currentUser->update(['check_notification' => true]);
        $response = array(
            'status' => 'success'            
        );
        return response()->json($response);         
    }

    protected static function  isShowNotification($currentUser, $course, $currentDayCourse)
    {
        $diffPeriodCourse_currentDayCourse = $course->period - $currentDayCourse;            
        if($diffPeriodCourse_currentDayCourse <= $course->notification_day_number && !$currentUser->check_notification) {
            return $course->notification;
        } else {
            return false;
        }
    }

    public function show_training($course_slug,$item_slug)
    {
        $course = $this->courses->findWithParams(['slug' => $course_slug])->firstOrFail();
        $currentUser = Auth::user();       
        $item = Item::where(['slug' => $item_slug])->where(['course_id' => $course->id])->firstOrFail();    
        $training_schedule = $course->training_schedule; 
        $numberDay = null;
        foreach ($training_schedule as $key => $value) {
            if($value['item_id'] == $item->id) {
                $numberDay = str_replace('day_','',$key); 
            }
        }
        $current_training = $item->users()->wherePivot('user_id', $currentUser->id)->first();
        if(isset($current_training)) {
            $traininig_status = $current_training->pivot->is_done;
        } else {
            $traininig_status = NULL;
        }       
        $this->setTitle($course->name . ' - Тренировка День ' . $numberDay .' | Неделя ' .ceil($numberDay/7)); 
        return view('my_acount/pages/trainings/trainings_item', compact(['item', 'numberDay', 'traininig_status']));
    }

    public function set_training_status(Request $request)
    {
        $currentUser = Auth::user();  
        $training_id = $request->get('training_id');
        
        $current_training = $currentUser->items()->wherePivot('item_id',$training_id)->first();
        if(isset($current_training)) {
            $training_status = $current_training->pivot->is_done;
            if(isset($training_status) && $training_status == 1) {
                $currentUser->items()->sync([
                    $training_id => ['is_done' => 0]
                ]);               
                $response = array(
                    'status' => 'success',                
                    'is_done' => false,                     
                );
            } elseif (isset($training_status) && $training_status == 0) {
                $currentUser->items()->sync([
                    $training_id => ['is_done' => 1]
                ], false);  
                $response = array(
                    'status' => 'success',                
                    'is_done' => true,                     
                );
            } else {
                $currentUser->items()->attach($training_id, ['is_done' => 1]); 
                $response = array(
                    'status' => 'success',                
                    'is_done' => true,                     
                );
            }  
        } else {
            $currentUser->items()->attach($training_id, ['is_done' => 1]); 
            $response = array(
                'status' => 'success',                
                'is_done' => true,                     
            );
        }
                     
              
        return response()->json($response);          
    }

    public function show_category_items($category_slug)
    {     
        $currentUser = Auth::user(); 
        $course_id = $currentUser->course_id;
        $course = Courses::find($course_id);       
        $category = Category::where('slug', $category_slug)->firstOrFail();       
        $categoryItems = $category->items;
        $items = array();
        foreach ($categoryItems as $item) {
            $curItem = $item->courses()->wherePivot('course_id', $course_id)->get();
            if($curItem->count() != 0 && $item->is_active) {
                array_push($items, $item);
            }
        }        
        $this->setTitle('Личный кабинет - '. $category->name);
        $page_title = $category->name;
        $description = $category->description;
        return view('my_acount/pages/items/categories_items', compact(['category', 'items', 'page_title', 'description', 'course']));
    }

    public function show_item($category_slug, $item_slug)
    { 
        $category = Category::where(['slug' => $category_slug])->firstOrFail();
        $item = Item::where(['category_id' => $category->id])->where(['slug' => $item_slug])->firstOrFail();   
        $this->setTitle($item->title);     
        return view('my_acount/pages/items/item', compact([ 'item']));
    }

    public function show_results(Request $request)
    {   
        $currentUser = Auth::user();
        $results = $currentUser->results;  
        $id_last_result = $currentUser->id_last_result;  
        $this->setTitle('Личный кабинет - Результати');     
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
        $this->setTitle('Личный кабинет - Добавить отчет'); 
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
        return redirect()->route('show_results'); 
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

    public function food_regulations(Request $request, $course_slug)
    {
        $currentCourse = $this->courses->findWithParams(['slug'=>$course_slug])->first();
        $food_regulations = $currentCourse->food_regulations;
        $this->setTitle($currentCourse->name . ' - Основные правила питания'); 
        return view('my_acount/pages/food_regulations/food_regulations', compact([ 'food_regulations']));
    }    

    public function motivations(Request $request)
    {        
        $motivations = Motivations::getMotivations();
        $settings_motivation = Motivations::getMotivationsSettings();
        $period_motivation = $settings_motivation['period_motivation'];
        $interval = $settings_motivation['interval'];
        $currentUser = Auth::user();        
        $currentDate = Carbon::now();
        if(isset($currentUser->last_time_motivation)) {
            $last_time_motivation = Carbon::createFromFormat('Y-m-d H:i:s', $currentUser->last_time_motivation); 
        } else {
            $last_time_motivation = $currentDate->subMinutes($period_motivation);
            $currentUser->update([                
                'last_time_motivation' => $last_time_motivation,
            ]);
        }  

        $last_id = $currentUser->last_id_motivation;
        $diffMinutes = $last_time_motivation->diffInMinutes($currentDate, false);
      
        if($diffMinutes >= $period_motivation) {
            if(($last_id + 1) >= count($motivations)) {
                $last_id = 0;
            } else {
                $last_id++;
            }
            $currentUser->update([
                'last_id_motivation' => $last_id,
                'last_time_motivation' => $currentDate
            ]);
            $response = array(
                'status' => 'success',
                'motivations' => $motivations,
                'period_motivation' => $period_motivation,
                'last_id' => $last_id,
                'interval' => $interval, 
                'show_motivation' => true,                     
            );
        } else {
            $response = array(
                'status' => 'success',
                'motivations' => $motivations,
                'period_motivation' => $period_motivation,
                'last_id' => $last_id,
                'interval' => $interval, 
                'show_motivation' => false,                     
            );
        }        
        return response()->json($response);  
    }

    public function is_show_motivation(Request $request)
    {
        $motivations = Motivations::getMotivations();
        $settings_motivation = Motivations::getMotivationsSettings();
        $period_motivation = $settings_motivation['period_motivation'];
        $currentUser = Auth::user();
        $currentDate = Carbon::now();
        if(isset($currentUser->last_time_motivation)) {
            $last_time_motivation = Carbon::createFromFormat('Y-m-d H:i:s', $currentUser->last_time_motivation); 
        } else {
            $last_time_motivation = $currentDate->subMinutes($period_motivation);
            $currentUser->update([                
                'last_time_motivation' => $last_time_motivation,
            ]);
        }
        
        $last_id = $currentUser->last_id_motivation;         
        $diffMinutes = $last_time_motivation->diffInMinutes($currentDate, false);
      
        if($diffMinutes >= $period_motivation) {
            if(($last_id + 1) >= count($motivations)) {
                $last_id = 0;
            } else {
                $last_id++;
            }
            $currentUser->update([
                'last_id_motivation' => $last_id,
                'last_time_motivation' => $currentDate
            ]);
            $response = array(
                'status' => 'success',
                'show_motivation' => true,                
                'last_id' => $last_id                                   
            );
        } else {
            $response = array(
                'status' => 'success',
                'show_motivation' => false,                
                'last_id' => $last_id                                   
            );
        }
        return response()->json($response); 
    }


}
