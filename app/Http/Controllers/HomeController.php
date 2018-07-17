<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Settings;
use App\Social;
use App\Courses;
use App\Marathons;
use App\User;
use App\UserSoul;
use App\Order;
use Session;
use Mail;
use Log;
use Carbon\Carbon;

use GuzzleHttp\Client;


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
        $courses = Courses::where('is_active', true)->where('type', 'cours')->get();    
        $active_marathons = Courses::where('is_active', true)->where('type', 'marathon')->get();
        $marathons = array();            
         
        if(isset($active_marathons)) {
          foreach ($active_marathons as $marathon) {
            $currentDate = Carbon::now();  
            $dataStartMarathon = Carbon::createFromFormat('Y-m-d', $marathon->date_end_selection);
            $diffDays = $currentDate->diffInDays($dataStartMarathon, false); 
            $dataEndMarathon = $dataStartMarathon->addDay($marathon->period);
            $diffDaysEdnMarathon = $currentDate->diffInDays($dataEndMarathon, false);
            if($diffDaysEdnMarathon >= 0) {
              if($diffDays > 0) {
                $dt = Carbon::parse($marathon->date_end_selection);              
                $marathon->message = 'Cтарт марафона ' . $dt->day ." ".$monthes[$dt->month];
              } 
              array_push($marathons, $marathon);
            }         
          }                       
        }  
        

        
        return view('front.home', compact(['settings', 'instagram', 'courses', 'marathons' ,'marathon_message']));

    }

    public function password_reset(Request $request) {        
        $message = "";
        $userEmail = $request->get('email');
        $settings = Settings::first();                      
        $user = $users = User::where('email', $userEmail)->firstOrFail();  

        $newUserPass = str_random(8);
        $hashNewPass = bcrypt($newUserPass); 
        $user->update(['password' => $hashNewPass]); 

        $currentMessage = "Ваш пароль обновлен. ";     
        $currentMessage = $currentMessage . "\n" . $message;

        $params = array();
        $params['user_email'] = $user->email;
        $params['admin_email'] = $settings->email;  

        Mail::send('emails.reset_password',array('user_name' =>$user->email, 'user_password'=>$newUserPass, 'curMessage' => $currentMessage), function($message) use($params)
        {
            $message->from($params['admin_email'], 'gizerskaya - Фитнесс Тренер');

            $message->to($params['user_email'])->subject('gizerskaya - Фитнесс Тренер');

        });

        Session::flash('success', 'Мы отправили вам по электронной почте новые данные для входа в Личный кабинет!');
        return redirect()->back();
    }

    public function test_message()
    {
        $user_name = 'vova';
        $newUserPass = 3243432;
        $currentMessage = "Ваш пароль обновлен. ";
         Mail::send('emails.reset_password',array('user_name' =>$user_name, 'user_password'=> $newUserPass, 'curMessage' => $currentMessage), function($message)
        {
            $message->from('test@gmail.com', 'vova');

            $message->to('vhrebenko@gmail.com');

        });
    }
   
    public function register_user($slug)
    {      
        $course = Courses::where('slug', $slug)->firstOrFail();  
        return view('auth.register', compact(['course']));
    }

    // validate email user ajax request from frontend
    public function validate_email_user(Request $request)
    {
        $curEmail = $request->get('email');
        $statusEmail = $request->get('status_email');
        $users = User::where('email', $curEmail)->get();       
         
        if($statusEmail == 'issetEmail'){
          if($users->count() > 0) {
              return response()->json(false);
          } else {
              return response()->json(true);
          }
        } else if($statusEmail == 'noIssetEmail' ) {
          if($users->count() > 0) {
              return response()->json(true);
          } else {
              return response()->json(false);
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

        $newUserOrder = Order::create([
            'user_id' => $newSoulUser->id,
            'course_id' => $course->id,
            'status_id' => 2, // В ожидании оплаты    
            'user_status' => 0, // Пользователь не зарегистрирован
            'total' => $course->price,                
        ]); 

        $request->session()->put('id_user_order', $newUserOrder->id);

        return redirect()->route('oplata');
    }

    public function oplata(Request $request) {
      $id_user_order = $request->session()->get('id_user_order');
      $currentOrder = Order::find($id_user_order);       
       
      $course = Courses::find($currentOrder->course_id);
      $price = number_format((float)$course->price, 2, '.', '');   
      $date = Carbon::now()->addHour();    
          //Секретный ключ интернет-магазина
      $key = "506462706f727c57375f36775652724449475a4d316b6a6b5e6c5d";
   
      $fields = array();
   
        // Добавление полей формы в ассоциативный массив
      $fields["WMI_MERCHANT_ID"]    = "117327853980";
      $fields["WMI_PAYMENT_AMOUNT"] = $price;
      $fields["WMI_CURRENCY_ID"]    = "643";  
      $fields["WMI_DESCRIPTION"]    = "Оплата Курса ".strip_tags($course->name);
      $fields["WMI_SUCCESS_URL"]    = route('success_oplata');
      $fields["WMI_FAIL_URL"]       = route('error_oplata');
      $fields["WMI_EXPIRED_DATE"] = $date;
      $fields["WMI_PAYMENT_NO"] = $id_user_order;
      
      //Если требуется задать только определенные способы оплаты, раскоментируйте данную строку и перечислите требуемые способы оплаты.
      //$fields["WMI_PTENABLED"]      = array("UnistreamRUB", "SberbankRUB", "RussianPostRUB");
   
      //Сортировка значений внутри полей
      foreach($fields as $name => $val)
      {
          if(is_array($val))
          {
            usort($val, "strcasecmp");
            $fields[$name] = $val;
          }
      }
   
      // Формирование сообщения, путем объединения значений формы,
      // отсортированных по именам ключей в порядке возрастания.
      uksort($fields, "strcasecmp");
      $fieldValues = "";
   
      foreach($fields as $value)
      {
        if(is_array($value))
            foreach($value as $v)
            {
                //Конвертация из текущей кодировки (UTF-8)
                //необходима только если кодировка магазина отлична от Windows-1251
                $v = iconv("utf-8", "windows-1251", $v);
                $fieldValues .= $v;
            }
        else
        {
            //Конвертация из текущей кодировки (UTF-8)
            //необходима только если кодировка магазина отлична от Windows-1251
            $value = iconv("utf-8", "windows-1251", $value);
            $fieldValues .= $value;
        }
      }
   
      // Формирование значения параметра WMI_SIGNATURE, путем
      // вычисления отпечатка, сформированного выше сообщения,
      // по алгоритму MD5 и представление его в Base64
   
      $signature = base64_encode(pack("H*", md5($fieldValues . $key)));
   
      //Добавление параметра WMI_SIGNATURE в словарь параметров формы
   
      $fields["WMI_SIGNATURE"] = $signature;


        return view('front/pages/oplata/oplata', compact(['fields', 'price', 'course', 'date']));
    }




    public function success_oplata(Request $request)
    { 
      return view('front/pages/oplata/succes');
    }  

    public function error_oplata(Request $request)
    {       
      return view('front/pages/oplata/error');
    }

    public function oplata_result() 
    {   

        // Секретный ключ интернет-магазина (настраивается в кабинете) 
        $skey = "506462706f727c57375f36775652724449475a4d316b6a6b5e6c5d";

        // Проверка наличия необходимых параметров в POST-запросе 
        if (!isset($_POST["WMI_SIGNATURE"])) {        
            Log::error('Отсутствует параметр WMI_SIGNATURE');
        }   

        if (!isset($_POST["WMI_ORDER_STATE"])) {
            Log::error('Отсутствует параметр WMI_ORDER_STATE');
        }

        // Извлечение всех параметров POST-запроса, кроме WMI_SIGNATURE 
        foreach($_POST as $name => $value)
        {
            if ($name !== "WMI_SIGNATURE") $params[$name] = $value;
        }

        // Сортировка массива по именам ключей в порядке возрастания
        // и формирование сообщения, путем объединения значений формы
     
        uksort($params, "strcasecmp"); $values = "";
     
        foreach($params as $name => $value)
        { 
            $values .= $value;
        }

        // Формирование подписи для сравнения ее с параметром WMI_SIGNATURE
     
        $signature = base64_encode(pack("H*", md5($values . $skey)));
     
        //Сравнение полученной подписи с подписью W1 

        if ($signature == $_POST["WMI_SIGNATURE"])
        {
            if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED")
            {
                if(isset($_POST["WMI_PAYMENT_NO"]))
                {
                    $id_user_order = $_POST["WMI_PAYMENT_NO"];
                    Log::info('Заказ № '. $_POST["WMI_PAYMENT_NO"] .' оплачен');
                    $currentOrder = Order::find($id_user_order);      
                    if(isset($currentOrder->user_status) && $currentOrder->user_status)
                    { 
                        self::succesOplataRegisteredUser($currentOrder->user_id, $currentOrder->course_id, $currentOrder); 
                        Log::info('Заказ № '. $_POST["WMI_PAYMENT_NO"] .' оплачен');
                        exit(); 
                    } else if (isset($currentOrder->user_status) && !$currentOrder->user_status) {                         

                        self::succesOplataNotRegisteredUser($currentOrder->user_id, $currentOrder->course_id, $currentOrder);
                        Log::info('Заказ № '. $_POST["WMI_PAYMENT_NO"] .' оплачен');
                        exit();   
                    }                
                    
                } else {
                Log::error('Отсутствует параметр WMI_PAYMENT_NO');
                }            
            } else {
                Log::error("Неверное состояние ". $_POST["WMI_ORDER_STATE"]);
            }
        } else {
            // Подпись не совпадает, возможно вы поменяли настройки интернет-магазина
            Log::error("Неверная подпись " . $_POST["WMI_SIGNATURE"]);
        }
        
    }

    protected static function  succesOplataNotRegisteredUser($user_id, $course_id, $currentOrder)
    {
        $settings = Settings::first();
        $user_soul = UserSoul::find($user_id);
        $new_user = array();
        $new_user['status_id'] = 0;    // Выключен. Еще не вводил результати.         
        $newUserPass = str_random(8);
        $new_user['password'] = bcrypt($newUserPass);   
        $new_user['role_id'] = 3; 
        $new_user['name'] = $user_soul->name;
        $new_user['phone'] = $user_soul->phone;
        $new_user['email'] = $user_soul->email; 
        $new_user['course_id']  = $course_id;
        $currentCourse = Courses::find($course_id);
        if(isset($currentCourse)) {
            if($currentCourse->type == 'cours') {
              $new_user['data_start_course']  = Carbon::now();   
            } elseif ($currentCourse->type == 'marathon') {
              $new_user['data_start_course']  = $currentCourse->date_end_selection;           
            } 
        }
        $user = User::create($new_user);  
        $currentOrder->update([
            'status_id' => 1,
            'user_status' => 1,
            'user_id' => $user->id,
        ]);    
        $user_soul->delete();      
        $user->roles()->attach([
            $new_user['role_id']
        ]);  

        $params = array();
        $params['user_email'] = $new_user['email'];
        $params['admin_email'] = $settings->email; 

        Mail::send('emails.user',array('user_name' =>$new_user['email'], 'user_password'=>$newUserPass), function($message) use ($params)
        {
            $message->from($params['admin_email'], 'gizerskaya - Фитнесс Тренер');
            $message->to($params['user_email'])->subject('gizerskaya - Фитнесс Тренер');
        });

         /* mail($new_user['email'],
          "gizerskaya - Фитнесс Тренер",
          "Спасибо, что нас выбрали. \nВаши данные для входа в Ваш Личный Кабинет:\nЛогин: " . $new_user['email'] . "\nПароль: " . $newUserPass . "",
          "From:".$settings->email."\r\n"."X-Mailer: PHP/" . phpversion());*/

        Mail::send('emails.admin',array('user_name' =>$new_user['name'], 'user_email'=>$new_user['email'], 'user_tel' => $new_user['phone']), function($message) use ($params)
        {
            $message->from('site@gizerskaya.com', 'gizerskaya - Фитнесс Тренер');
            $message->to($params['admin_email'])->subject('gizerskaya - Фитнесс Тренер');

        }); 

        /* mail($settings->email,
          "gizerskaya - Фитнесс Тренер",
          "У Вас появился новичок : " . $new_user['email'],
          "From:"."gizerskaya - Фитнесс Тренер"."\r\n"."X-Mailer: PHP/" . phpversion());*/
    }

    protected static function  succesOplataRegisteredUser($user_id, $course_id, $currentOrder)
    {
        $settings = Settings::first();
        $currentOrder->update(['status_id' => 1]);
        $currentUser = User::find($user_id);
        $currentUser->update(['course_id' => $course_id]);   
        $currentCourse = Courses::find($course_id);
        if(isset($currentCourse)) {
            if($currentCourse->type == 'cours') {
                $currentDate = Carbon::now();
                $currentUser->update(['data_start_course' => $currentDate]);              
            } elseif ($currentCourse->type == 'marathon') {
                $currentUser->update(['data_start_course' => $currentCourse->date_end_selection]);        
            } 
        }
    }
}    