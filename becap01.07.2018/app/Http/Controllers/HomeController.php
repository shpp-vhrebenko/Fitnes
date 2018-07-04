<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Settings;
use App\Social;
use App\Courses;
use App\Marathons;
use App\User;
use App\UserSoul;
use Session;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        
    )
    {
        $settings = Settings::first();     
        view()->share(compact([ 'settings'])); 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monthes = array("Нулября","Января","Февраля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября","Октября","Ноября","Декабря");
        $instagram = Social::firstOrFail();             
        $settings = Settings::first();  
        $course = Courses::where('is_active', true)->where('type', 'cours')->first();    
        $marathon = Courses::where('is_active', true)->where('type', 'marathon')->first(); 
        $marathon_message = '';        
         
        if(isset($marathon)) {
            $currentDate = Carbon::now();  
            $dataStarttMarathon = Carbon::createFromFormat('Y-m-d', $marathon->date_end_selection);
            $diffDays = $currentDate->diffInDays($dataStarttMarathon, false);            
            if($diffDays > 0) {
                $dt = Carbon::parse($marathon->date_end_selection);
                $marathon_message = 'Cтарт марафона ' . $dt->day ." ".$monthes[$dt->month];
            }            
        }  
        
        
        return view('front.home', compact(['settings', 'instagram', 'course', 'marathon' ,'marathon_message']));

    }

    public function register_user($slug)
    {      
        $course = Courses::where('slug', $slug)->firstOrFail();  
        return view('auth.register', compact(['course']));
    }

    public function validate_email_user(Request $request)
    {
        if($curEmail = $request->get('email')) {
            $users = User::where('email', $curEmail)->get();            
            if($users->count() > 0) {
                return response()->json(false);
            } else {
                return response()->json(true);
            }
        } else {
             return response()->json(false);
        }       
    }

    public function user_store(Request $request, $slug) {
        $course = Courses::where('slug', $slug)->first();         
        $newSoulUser = UserSoul::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),            
            'phone' => $request->get('phone'),
            'course_id' => $course->id,           
        ]);       
        $request->session()->put('id_soul_user', $newSoulUser->id);

        return redirect()->route('oplata');
    }

    public function oplata() {
        return view('front/pages/oplata/oplata');
    }

    public function oplata_course(Request $request) { 
        $check_course = $request->get('check_course');      
        if($check_course && ($check_course === "on" )) {
            $id_soul_user = $request->session()->get('id_soul_user');
            $user_soul = UserSoul::find($id_soul_user);
            $new_user = array();
            $new_user['status_id'] = 1;                            
            $new_user['remember_token'] = $request->get('_token');       
            $newUserPass = str_random(8);
            $new_user['password'] = bcrypt($newUserPass);   
            $new_user['role_id'] = 3; 
            $new_user['name'] = $user_soul->name;
            $new_user['phone'] = $user_soul->phone;
            $new_user['email'] = $user_soul->email; 
            $new_user['course_id']  = $user_soul->course_id;
            $currentCourse = Courses::where(['id' => $user_soul->course_id])->first();
            if(isset($currentCourse)) {
                if($currentCourse->type == 'cours') {
                    $new_user['data_start_course']  = Carbon::now();   
                } elseif ($currentCourse->type == 'marathon') {
                    $new_user['data_start_course']  = $currentCourse->date_end_selection;                     
                } 
            }                         
            $user = User::create($new_user);  
            $user_soul->delete();      
            $user->roles()->attach([
                $new_user['role_id']
            ]);   

            $settings = Settings::first(); 
            mail($new_user['email'],
                "gizerskaya - Фитнесс Тренер",
                "Спасибо, что нас выбрали. \nВаши данные для входа в Ваш Личный Кабинет:\nЛогин: " . $new_user['email'] . "\nПароль: " . $newUserPass . "",
                "From:".$settings->email."\r\n"."X-Mailer: PHP/" . phpversion());

            mail($settings->email,
                "gizerskaya - Фитнесс Тренер",
                "У Вас появился новичок : " . $new_user['email'],
                "From:"."gizerskaya - Фитнесс Тренер"."\r\n"."X-Mailer: PHP/" . phpversion());

            $request->session()->flush();
            return redirect()->route('success_oplata');
        } else {
            return redirect()->route('error_oplata');
        }
    }

    public function success_oplata()
    {
        return view('front/pages/oplata/succes');
    }

    public function error_oplata()
    {
        return view('front/pages/oplata/error');
    }
}
