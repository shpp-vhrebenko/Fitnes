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

    public function test_message()
    {
        Mail::send('emails.welcome',array('body' =>'body', 'title'=>'title'), function($message)
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

    public function oplata(Request $request) {
    $id_soul_user = $request->session()->get('id_soul_user');
    $user = UserSoul::find($id_soul_user); 
    $course = Courses::find($user->course_id);
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


        return view('front/pages/oplata/oplata', compact(['signature', 'price', 'course', 'date']));
    }

    public function oplata_course(Request $request) { 
        $check_course = $request->get('check_course');      
        if($check_course && ($check_course === "on" )) {
            $id_soul_user = $request->session()->get('id_soul_user');
            $user_soul = UserSoul::find($id_soul_user);
            $new_user = array();
            $new_user['status_id'] = 0;                            
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

            Order::create([
                'user_id' => $user->id,
                'status_id' => 1,            
                'total' => $currentCourse->price                
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



    public function success_oplata(Request $request)
    {
      Log::info('Оплачено!!!');
      $id_soul_user = $request->session()->get('id_soul_user');
      $user_soul = UserSoul::find($id_soul_user);
      $new_user = array();
      $new_user['status_id'] = 0;                            
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

      Order::create([
          'user_id' => $user->id,
          'status_id' => 1,            
          'total' => $currentCourse->price                
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
      
      return view('front/pages/oplata/succes');
    }  

    public function error_oplata(Request $request)
    {       
      return view('front/pages/oplata/error');
    }

    public function oplata_result() 
    {
      Log::info('Результат оплаты');
      dd('result');
       // Секретный ключ интернет-магазина (настраивается в кабинете)
 
  $skey = "506462706f727c57375f36775652724449475a4d316b6a6b5e6c5d";
 
  // Функция, которая возвращает результат в Единую кассу
 
  function print_answer($result, $description)
  {
    print "WMI_RESULT=" . strtoupper($result) . "&";
    print "WMI_DESCRIPTION=" .$description;
    exit();
  }
 
  // Проверка наличия необходимых параметров в POST-запросе
 
  if (!isset($_POST["WMI_SIGNATURE"]))
    /*print_answer("Retry", "Отсутствует параметр WMI_SIGNATURE");*/
    Log::info('Отсутствует параметр WMI_SIGNATURE');
 
  if (!isset($_POST["WMI_PAYMENT_NO"]))
   /* print_answer("Retry", "Отсутствует параметр WMI_PAYMENT_NO");*/
  Log::info('Отсутствует параметр WMI_PAYMENT_NO');
 
  if (!isset($_POST["WMI_ORDER_STATE"]))
    /*print_answer("Retry", "Отсутствует параметр WMI_ORDER_STATE");*/
  Log::info('Отсутствует параметр WMI_ORDER_STATE');

 
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
      // TODO: Пометить заказ, как «Оплаченный» в системе учета магазина
 
    /*  print_answer("Ok", "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " оплачен!");*/
      Log::info('Заказ оплачен');

    }
    else
    {
      // Случилось что-то странное, пришло неизвестное состояние заказа
 
     /* print_answer("Retry", "Неверное состояние ". $_POST["WMI_ORDER_STATE"]);*/
       Log::info("Неверное состояние ". $_POST["WMI_ORDER_STATE"]);
    }
  }
  else
  {
    // Подпись не совпадает, возможно вы поменяли настройки интернет-магазина
 
 /*   print_answer("Retry", "Неверная подпись " . $_POST["WMI_SIGNATURE"]);*/
    Log::info("Неверная подпись " . $_POST["WMI_SIGNATURE"]);
  }
    }
}
