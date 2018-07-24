<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingsRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\EditClientRequest;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Requests\TrainingRequest;
use App\Http\Requests\TrainingUpdateRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\StoreCoursRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Http\Requests\StoreMarathonsRequest;
use App\Http\Requests\MarathonsUpdateRequest;
use App\Http\Requests\MotivatorRequest;

use App\Repositories\UsersRepositoryInterface;
use App\Repositories\CategoriesRepositoryInterface;
use App\Repositories\ItemsRepositoryInterface;
use App\Repositories\ResultsRepositoryInterface;
use App\Repositories\CoursesRepositoryInterface;
use App\Repositories\OrdersRepositoryInterface;
use App\Repositories\MotivationsRepositoryInterface;

use App\User;
use App\UserSoul;
use App\Settings;
use App\Social;
use App\Role;
use App\Category;
use App\Item;
use App\Result;
use App\Courses;
use App\Order;
use App\Motivations;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

use Mail;

/**
 
 * @property UsersRepositoryInterface users 
 */
class AdminController extends Controller
{

    public function __construct(      

        ItemsRepositoryInterface                    $itemsRepository,
        UsersRepositoryInterface                    $usersRepository,
        CategoriesRepositoryInterface           $categoriesRepository,
        ResultsRepositoryInterface              $resultsRepository,     
        CoursesRepositoryInterface              $coursesRepository,
        OrdersRepositoryInterface               $ordersRepository,
        MotivationsRepositoryInterface               $motivationsRepository

    )
    {        
        $this->items = $itemsRepository;
        $this->users = $usersRepository;        
        $this->categories = $categoriesRepository;
        $this->courses = $coursesRepository;  
        $this->orders = $ordersRepository;
        $this->motivations = $motivationsRepository;         
    }

    public function index()
    {
        $orders = $this->orders->all();
        $total_income = 0;
        foreach ($orders as $order) {
            if($order->status_id == 1) {
                $total_income = $total_income + $order->total;
            }
        }   

        $stats = [            
            'users' => $this->users->count(),
            'orders' => $this->orders->count(),
            'total' => $total_income,
        ];  



        $latest_orders = $this->orders->latest(5)->get();       

        return view('admin/index', compact(['stats', 'latest_orders']));
    }

    // MOTIVATIONS

    public function motivations(Request $request)
    {
        $title = 'Записи мотиватора';
        $motivations = $this->motivations->order('id', 'desc')->paginate(10);      
        return view('admin/pages/motivations/index', compact(['title', 'motivations']));
    }

    public function motivations_new()
    {
        $title = 'Создание записи мотиватора';        
        return view('admin/pages/motivations/new', compact(['title']));
    }

    public function motivations_store(MotivatorRequest $request)
    {
        $item = $request->get('item'); 
        $this->motivations->create($item);
        Session::flash('success', 'Запись мотиватора успешно создана!');
        return redirect()->back();
    }

    public function motivations_edit($id)
    {
        $item = $this->motivations->find($id);              
        if (!isset($item)) {
            return abort('404');
        }            
        $title = 'Просмотр записи мотиватора';
        return view('admin/pages/motivations/new', compact(['title','item']));
    }

    public function motivations_update(MotivatorRequest $request, $id) 
    {
        $motivator = $this->motivations->find($id);
        if (!isset($motivator)) {
            return abort('404');
        }
        $motivator->update($request->get('item'));
        Session::flash('success', 'Запись мотиватора успешно изменена!');
        return redirect()->back();
    }

    public function motivations_destroy($id)
    {
        $motivator = $this->motivations->find($id);        
        if (!isset($motivator)) {
            return abort(404);
        }    
        $motivator->delete();
        Session::flash('success', 'Запись мотиватора успешно удалена!');
        return redirect()->route('motivations');
    }

    // SETTINGS

    public function settings()
    {
        $title = 'Настройки';
        $settings = Settings::first();       
        $social = Social::get();
        return view('admin/pages/settings/index', compact(['title', 'settings', 'social']));
    }

    public function settings_update(StoreSettingsRequest $request)
    {
        $settings = Settings::first(); 
        if( $image = $request->file('logo') )
        {
            File::delete( public_path('uploads/logo/'. Settings::first()->logo ));
            $newLogo = Settings::saveImage( $image, 'logo' );
            $settings->update(['logo' => $newLogo]);       
        }

        if( $image = $request->file('favicon') )
        {
            File::delete( public_path('uploads/favicon/'. Settings::first()->logo ));
            $newFavicon = Settings::saveImage( $image, 'favicon' );
            $settings->update(['favicon' => $newFavicon]);       
        }

        $settingsRequest = $request->get('settings'); 
        $socialRequest = $request->get('social');

        if (isset($settingsRequest)) {            
            $settings->update($settingsRequest);
        }        
        if (isset($socialRequest)) {
            foreach ($socialRequest as $key => $item)
            {
                if (isset($item)) {
                    Social::where('name', $key)->update(['link' => $item]);
                }
            }
        }

        Session::flash('success', 'Настройки успешно обновлены!');
        return redirect()->route('settings');
    }

    // END SETTINGS

    // ORDERS
    public function orders()
    {
        $title = 'Список заказов';
        $orders = $this->orders->order('id', 'desc')->paginate(10);
        $ordersStatuses = Order::getAllOrderStatuses();        
        return view('admin/pages/orders/index', compact(['orders', 'ordersStatuses', 'title']));
    }

    public function orders_filter(Request $request)
    {
        $filter_request = $request->get('filter');
        $c_filter = 0;

        foreach ($filter_request as $f_r)
        {
            if ($f_r != null)
            {
                $c_filter++;
            }
        }

        if (isset($filter_request) && $filter_request != null && $c_filter) {
            $orders = Order::query();
            foreach ($filter_request as $key => $filter) {
                if (isset($filter)) {
                    if ($key == 'created_at' || $key == 'updated_at') {
                        $orders->whereDate($key, $filter);
                    } elseif ($key == 'user_name') {
                        $orders->whereHas('client', function ($q) use ($filter) {
                            $q->where('name', 'like', '%' . $filter . '%');
                        });
                    } else {
                        $orders->where($key, $filter);
                    }
                }
            }

            $paginate = $orders->paginate(10);

             foreach ($filter_request as $key => $p_f)
            {
                if ($p_f != null)
                {
                    $paginate->appends('filter[' . $key . ']', $p_f);
                }
            }

            $this->setTitle('Фильтр заказов');
            $ordersStatuses = Order::getAllOrderStatuses();
            return view('admin/pages/orders/index')->with(['orders' => $paginate, 'ordersStatuses' => $ordersStatuses]);
        } else {
            return redirect()->back();
        }
    }

    public function order($id)
    {
        $order = $this->orders->find($id);              
        if (!isset($order)) {
            return abort('404');
        }        
        $course = $this->courses->find($order->course_id);     
        $this->setTitle('Просмотр заказа');
        return view('admin/pages/orders/show')->with(['order' => $order, 'course' => $course]);
        
    }

    public function edit_order($id)
    {
        $order = $this->orders->find($id);
        if (!isset($order)) {
            return abort('404');
        }
        $this->setTitle('Редактирование заказа');        
        return view('admin/pages/orders/edit')->with(['item' => $order]);
    }

    public function update_order(Request $request, $id)
    {
        $order = $this->orders->find($id);
        if (!isset($order)) {
            return abort('404');
        }
        $order->update($request->get('item'));
        Session::flash('success', 'Заказ успешно изменен!');
        return redirect()->back();
    }

    public function destroy_order($id)
    {
        $order = $this->orders->find($id);
        if (!isset($order))
        {
            return abort(404);
        }
        $order->delete();
        Session::flash('success', 'Заказ успешно удален!');
        return redirect()->route('orders');
    }
    // END ORDERS

    // CLIENTS

    public function clients(Request $request)
    {            
            $clients = $this->users->paginate(10);
            return view('admin/pages/clients/index')->with(['title' => 'Список клиентов', 'clients' => $clients]);
    }

    public function client($id)
    {
        $client = $this->users->find($id);
        if (!isset($client)) {
            return abort(404);
        }  
        $course = $this->courses->find($client->course_id);      
        return view('admin/pages/clients/show')->with(['title' => 'Профиль клиента: ' . $client->name, 'client' => $client, 'client_status' => 1, 'course' => $course]);
    }

    public function client_not_register($id)
    {
        $client = UserSoul::find($id);
        if (!isset($client)) {
            return abort(404);
        } 
        $course = NULL;   
        return view('admin/pages/clients/show')->with(['title' => 'Профиль не зарегистрированого клиента: ' . $client->name, 'client' => $client, 'client_status' => 0 , 'course' => $course]);

    }

    public function client_edit($id)
    {
        $client = $this->users->find($id);
        if (!isset($client)) {
            return abort(404);
        }   
        $currentCourse = $this->courses->find($client->course_id);
        if(isset($currentCourse) && $currentCourse->type == 'cours') {            
            $current_day_course = self::getCurrentCourseDayNumber($client->data_start_course);
        } else {
            $current_day_course = NULL;
        }           
        $roles = Role::all();
        $statuses = User::$userStatuses;
        $courses = $this->courses->findWithParams(['is_active' => true])->get();    
        return view('admin/pages/clients/new')->with([
            'title' => 'Редактирование клиента: ' . $client->name,
            'client' => $client,
            'roles' => $roles,
            'statuses' => $statuses,
            'courses' => $courses,
            'current_day_course' => $current_day_course,
        ]);
    }

    protected static function  getCurrentCourseDayNumber($data_start_course)
    {
        $currentDate = Carbon::now();       
        $dataStartCourse = Carbon::createFromFormat('Y-m-d H:i:s', $data_start_course);
        return $dataStartCourse->diffInDays($currentDate, false); 
    }

    public function client_update($id, EditClientRequest $request)
    {
        $settings = Settings::first(); 
        $client = $this->users->find($id);
        if (!isset($client)) {
            return abort(404);
        }
        $current_day_course = $request->get('current_day_course');
        $item = $request->get('item');
        $currentCourse = Courses::find($item['course_id']);
        $currentCourseType = $currentCourse->type;
        if($currentCourseType == 'cours') { 
            if(isset($client->data_start_course)) {
                $client_day_course = self::getCurrentCourseDayNumber($client->data_start_course);
            } else {
                $client_day_course = 0;
                $client->update(['data_start_course' => Carbon::now()]);
            }          
            
            if($current_day_course == 0) {
                $client->update(['data_start_course' => Carbon::now()]);
            } else if($current_day_course > $client_day_course) {
                $diffDays = $current_day_course - $client_day_course;
                $dataStartCourse = Carbon::createFromFormat('Y-m-d H:i:s', $client->data_start_course);
                $currentDataStartCourse = $dataStartCourse->subDays($diffDays);
                $client->update(['data_start_course' => $currentDataStartCourse]);
            } else if ($current_day_course < $client_day_course) {
                $diffDays = $client_day_course - $current_day_course;
                $dataStartCourse = Carbon::createFromFormat('Y-m-d H:i:s', $client->data_start_course);
                $currentDataStartCourse = $dataStartCourse->addDays($diffDays);
                $client->update(['data_start_course' => $currentDataStartCourse]);
            }
        }

        
        if(isset($item['password']) && !empty($item['password'])) {            
            $newUserPass = $item['password'];
            $item['password'] = bcrypt($newUserPass);
        } else {
            unset($item['password']);
        }

        $client->update($item);

        if(isset($item['password'])) {
            $params = array();
            $params['user_email'] = $item['email'];
            $params['admin_email'] = $settings->email; 
            Mail::send('emails.user',array('user_name' =>$item['email'], 'user_password'=>$newUserPass), function($message) use ($params)
            {
                $message->from($params['admin_email'], 'gizerskaya - Фитнесс Тренер');
                $message->to($params['user_email'] );

            });
        }
        if (!$client->hasRole($request->get('item')['role_id'])) {
            $client->updateRole($request->get('item')['role_id']);
        }
        Session::flash('success', 'Клиент успешно изменен!');
        return redirect()->back();
    }

    public function client_new()
    {              
        $roles = Role::all();
        $statuses = User::$userStatuses;
        $courses = $this->courses->findWithParams(['is_active' => true])->get();
        return view('admin/pages/clients/new')->with([
            'title' => 'Coздание клиента',            
            'roles' => $roles,
            'statuses' => $statuses,
            'courses' => $courses,
        ]);
    }

     public function client_store(StoreClientRequest $request)
    {
        $settings = Settings::first(); 
        $item = $request->get('item'); 
        $current_day_course = $request->get('current_day_course');        
        $currentCourse = Courses::find($item['course_id']);
        $currentCourseType = $currentCourse->type;  
        if(isset($current_day_course) && $currentCourseType == 'cours') {
            if($current_day_course == 0) {
                $item['data_start_course'] = Carbon::now(); 
            } else if ($current_day_course > 0) {
                $DataStartCourse = Carbon::now()->subDays($current_day_course);
                $item['data_start_course'] = $DataStartCourse; 
            }             
        } else {
            if(isset($currentCourse->date_end_selection)) {
                $item['data_start_course'] = Carbon::createFromFormat('Y-m-d', $currentCourse->date_end_selection);
            } else {
                $item['data_start_course'] = Carbon::now();
            }
            
        }

        $item['remember_token'] = $request->get('_token');    
        if(isset($item['password']) && !empty($item['password'])) {            
            $newUserPass = $item['password'];
            $item['password'] = bcrypt($newUserPass);
        } else {            
            $newUserPass = str_random(8);
            $item['password'] = bcrypt($newUserPass); 
        }


        $user = $this->users->create($item);        
        $user->roles()->attach([
            $item['role_id']
        ]);       

        $params = array();
        $params['user_email'] = $item['email'];
        $params['admin_email'] = $settings->email;  

        Mail::send('emails.user',array('user_name' =>$item['email'], 'user_password'=>$newUserPass), function($message) use ($params)
        {
            $message->from($params['admin_email'], 'gizerskaya - Фитнесс Тренер');
            $message->to($params['user_email'] );

        });

        /*mail($item['email'],
            "gizerskaya - Фитнесс Тренер",
            "Спасибо, что нас выбрали. \nВаши данные для входа в Ваш Личный Кабинет:\nЛогин: " . $item['email'] . "\nПароль: " . $newUserPass . "",
            "From:".$settings->email."\r\n"."X-Mailer: PHP/" . phpversion());*/

        Session::flash('success', 'Клиент успешно создан!'); 

        return redirect()->back();
    }

    public function client_destroy($id)
    {
        $client = $this->users->find($id);        
        if (!isset($client)) {
            return abort(404);
        }        
        $client->roles()->detach($client['role_id']);        
        $client->results()->delete();
        $client->items()->detach();
        $client->delete();
        Session::flash('success', 'Клиент успешно удален!');
        return redirect()->route('clients');
    }

    public function clients_filter(Request $request)
    {

        $filter_request = $request->get('filter');
        $c_filter = 0;

        foreach ($filter_request as $f_r)
        {
            if ($f_r != null)
            {
                $c_filter++;
            }
        }

        if (isset($filter_request) && $filter_request != null && $c_filter) {
            $clients = User::query();
            foreach ($filter_request as $key => $filter) {
                if (isset($filter)) {
                    if ($key == 'created_at' || $key == 'updated_at')
                    {
                        $clients->whereDate($key, $filter);
                    }
                    elseif ($key == 'name') {
                        $clients->where('name', 'like', '%'. $filter .'%')
                                ->orWhere('email', 'like', '%'. $filter .'%');
                    }
                    elseif ($key == 'company') {
                        $clients->where('company', 'like', '%'. $filter .'%');
                    }
                    elseif ($key == 'role_id') {
                        $clients->whereHas('roles', function ($q) use ($filter) {
                            $q->where('roles.id', $filter);
                        });
                    }
                    else {
                        $clients->where($key, $filter);
                    }
                }
            }

            $paginate = $clients->paginate(10);

          /*  foreach ($filter_request as $key => $p_f)
            {
                if ($p_f != null)
                {
                    $paginate->appends('filter[' . $key . ']', $p_f);
                }
            }     */     

            return view('admin/pages/clients/index')->with(['clients' => $paginate, 'title' => 'Список клиентов']);
        } else {
            return redirect()->back();
        }
    }

    public function client_sendMessage(Request $request) {        
        $message = "";
        $userId = $request->get('user_id');
        $settings = Settings::first();                      
        $user = $this->users->find($userId); 

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
            $message->from($params['admin_email']);

            $message->to($params['user_email'])->subject('gizerskaya - Фитнесс Тренер');

        });

        /* $currentMessage = "Ваш пароль обновлен. \nВаши данные для входа в Ваш Личный Кабинет:\nЛогин: " . $user->email . "\nПароль: " . $newUserPass . " "; 

        mail($user->email,
            "gizerskaya - Фитнесс Тренер",
            $currentMessage,
            "From:".$settings->email."\r\n"."X-Mailer: PHP/" . phpversion());*/       

        $response = array(
            'status' => 'success',
            'result' => 'Сообщение успешно отправлено!'
        );
        return response()->json($response); 
    }

    // END CLIENTS    

    // CATEGORY
     public function categories()
    {
        $title = 'Список категорий';
        $categories = $this->categories->paginate(10);
        return view('admin/pages/items/categories', compact(['title', 'categories']));
    }

    public function categories_new()
    {
        $title = 'Создание категории';
        $statuses = Category::$CategoryStatuses;
        return view('admin/pages/items/categories-new', compact(['title', 'statuses']));
    }

    public function categories_store(StoreCategoryRequest $request)
    {
        $item_request = $request->get('item');
        $item_request['slug'] = str_slug($item_request['name']);
        $category = $this->categories->create($item_request);       
        Session::flash('success', 'Категория успешно создана!');
        return redirect()->back();
    }

    public function edit_category($id)
    {
        $category = Category::find($id);
        $statuses = Category::$CategoryStatuses;
        if (!isset($category))
        {
            return abort('404');
        }
        $title = 'Изменение категории';        
        return view('admin/pages/items/categories-new', compact(['title', 'category', 'statuses']));
    }

    public function update_category(CategoryUpdateRequest $request, $id)
    {
        $category = $this->categories->find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $newCategory = $request->get('item');
        $searchCategory = $this->categories->findWithParams(['name' => $newCategory['name']])->first();      
       
        if(isset($searchCategory)) {
            if($searchCategory->id == $id) {
                $category->update($newCategory);                
                Session::flash('success', 'Категория успешно изменена!');
                return redirect()->back();
            } else {
                Session::flash('danger', 'Категория с таким Названием уже есть!');
                return redirect()->back();
            }
        } else {
            $newCategory['slug'] = str_slug($newCategory['name']);
            $category->update($newCategory);            
            Session::flash('success', 'Категория успешно изменена!');
            return redirect()->back();
        }
    }

    public function destroy_category($id)
    {
        $category = $this->categories->find($id);
        $itemsCategory = $category->items();       
        if(isset($itemsCategory)) {
            if($itemsCategory->count() > 0){
               Session::flash('danger', 'Категория не можит быть удалена так как в ней есть записи! Необходимо удалить все записи данной категории или назначить для них другую категорию!');
                return redirect()->route('categories'); 
            }            
        }
        if (!isset($category)) {
            return abort('404');
        }
        $category->delete();
        Session::flash('success', 'Категория успешно удалена!');
        return redirect()->route('categories');
    }
    // END CATEGORY

    // ITEMS 
      public function items()
    {
            $title = 'Список записей';
            $items = Item::where('category_id','!=', 1)->orderBy('id', 'asc')->paginate(10);
            $items->load('category');
            $categories = $this->categories->all(); 
            $courses = $this->courses->findWithParams(['is_active' => true])->get(); 
            return view('admin/pages/items/items', compact(['title', 'items', 'categories', 'courses']));
    }

    public function items_filter(Request $request)
    {
        $filter_request = $request->get('filter');
        $c_filter = 0;

        foreach ($filter_request as $f_r)
        {
            if ($f_r != null)
            {
                $c_filter++;
            }
        }

        if (isset($filter_request) && $filter_request != null && $c_filter) {
            $items = Item::query();
            foreach ($filter_request as $key => $filter) {
                if (isset($filter)) {
                    if ($key == 'created_at' || $key == 'updated_at')
                    {
                        $items->whereDate($key, $filter);
                    }
                    elseif ($key == 'title') {
                        $items->where('title', 'like', '%'. $filter .'%');
                    }
                    elseif ($key == 'category_id') {
                        $items->where('category_id', $filter);
                    }                                    
                    else {
                        $items->where($key, $filter);
                    }
                }
            }
            $paginate = $items->paginate(10);

           /* foreach ($filter_request as $key => $p_f)
            {
                if ($p_f != null)
                {
                    $paginate->appends('filter[' . $key . ']', $p_f);
                }
            }*/
            $categories = $this->categories->all(); 
            $courses = $this->courses->findWithParams(['is_active' => true])->get(); 
            $title = 'Список записей';
            return view('admin/pages/items/items')->with(['items' => $paginate , 'title' => $title, 'categories' => $categories, 'courses' => $courses]);

        } else {
            return redirect()->back();
        }
    }

    public function items_new()
    {
        $title = 'Создание записи';
        $categories = $this->categories->all(); 
        if($categories->count() == 0) {
            Session::flash('danger', 'Чтобы создать запись необходимо создать Категорию!!!');
            return redirect()->back();
        }
        $courses = $this->courses->findWithParams(['is_active' => true])->get(); 
        if($courses->count() == 0) {
            Session::flash('danger', 'Чтобы создать запись необходимо создать Курс или Марафон!!!');
            return redirect()->back();
        }
        $statuses = Item::$ItemStatuses;                         

        return view('admin/pages/items/new', compact(['title', 'categories', 'statuses', 'courses']));
    }

    public function items_store(ItemRequest $request)
    {
        $item = $request->get('item'); 
        $courses_array = $request->get('courses_array');
        $searchItem = Item::where(['category_id' => $item['category_id']])->where(['title' => $item['title']])->exists();         
            

        if($searchItem) {
            Session::flash('danger', 'Запись с таким Названием в выбранной Категории уже есть!');
            return redirect()->back();
        } else {
            $item['slug'] = str_slug($item['title']);
            if( $image = $request->file('item.image') )
            {            
                $item['image'] = Item::saveImage( $image );                 
            }        

            $currentItem = $this->items->create($item);            
            $currentItem->courses()->attach($courses_array);
            
            Session::flash('success', 'Запись успешно создана!');
            return redirect()->back();
        }
    }

    public function item_edit($id)
    {
        $item = $this->items->find($id);
        if (!isset($item))
        {
            return abort(404);
        }
        $title = 'Редактирование Записи';
        $categories = $this->categories->all(); 
        $statuses = Item::$ItemStatuses;
        $courses = $this->courses->findWithParams(['is_active' => true])->get();        
        $itemCourses = $item->courses;        
        $checkedCourses = array();
        foreach ($itemCourses as $key => $value) {
            $checkedCourses[$key] = $value->id;
        }       
        return view('admin/pages/items/new', compact(['title', 'categories', 'statuses', 'item', 'courses', 'checkedCourses']));
    }

    public function item_update(ItemUpdateRequest $request, $id)
    {       

        $item = $this->items->find($id);
        if (!isset($item))
        {
            return abort('404');
        }
        $newItem = $request->get('item');
        $courses_array = $request->get('courses_array');
        $searchItem = Item::where(['category_id' => $newItem['category_id']])->where(['title' => $newItem['title']])->first();        
       
        if(isset($searchItem)) {
            if($searchItem->id == $id) {                
                $item->update($newItem);
                $item->courses()->sync($courses_array);
                if( $image = $request->file('item.image') )
                {
                    File::delete( public_path('uploads/items/'. $item->image ));
                    $newImage = Item::saveImage( $image );
                    $item->update(['image' => $newImage]);       
                }
                Session::flash('success', 'Запись успешно изменена!');
                return redirect()->back();
            } else {
                Session::flash('danger', 'Запись с таким Названием в выбранной Категории уже есть!');
                return redirect()->back();
            }
        } else {
            $newItem['slug'] = str_slug($newItem['title']);
            $item->update($newItem);
            $item->courses()->sync($courses_array);
            if( $image = $request->file('item.image') )
            {
                File::delete( public_path('uploads/items/'. $item->image ));
                $newImage = Item::saveImage( $image );
                $item->update(['image' => $newImage]);       
            }
            Session::flash('success', 'Запись успешно изменена!');
            return redirect()->back();
        }
        
    }

    public function item_destroy($id)
    {
        $item = $this->items->find($id);
        if (!isset($item))
        {
            return abort(404);
        }
        if( $item->image != 'no-image.png')
        {
            File::delete( public_path('uploads/items/'. $item->image ));                   
        }  
        $item->courses()->detach();
        $item->delete();
        Session::flash('success', 'Запись успешно удалена!');
        return redirect()->route('admin_items');
    }

    // END ITEMS

    // RESULTS
    public function results($user_id)
    {
        $client = $this->users->find($user_id);
        $title = 'Результаты пользователя '.$client->name;
        $results = $client->results()->paginate(10); 
        return view('admin/pages/results/results', compact(['title', 'results']));
    }
    // END RESULTS

    // COURSES
    public function show_cours($id) 
    {      
        $course = $this->courses->find($id);
        if (!isset($course))
        {
            return abort(404);
        }
        $title = $course->name;                
        return view('admin/pages/courses/show', compact(['title', 'course']));
    } 

    public function show_courses() 
    {   
        $title = "Курсы";
        $courses = $this->courses->findWithParams(['type' => 'cours'])->get();      
        return view('admin/pages/courses/index', compact(['title', 'courses']));
    }  

    public function courses_filter()
    {
        
    }

    public function new_cours()
    {       
        $title = 'Создание курса';      
        $statuses = Courses::$coursStatuses;        
        return view('admin/pages/courses/new', compact(['title', 'statuses']));
    }

    public function course_trainings($id_course)
    {
        $curentCourse = $this->courses->find($id_course);
        $typeCourse = $curentCourse->type;  
            
        $trainings = $curentCourse->training_schedule;
       
        $title = 'Создание тренировок';     
            
        return view('admin/pages/courses/trainings', compact(['title', 'trainings', 'id_course','typeCourse']));
    }    

    public function cours_store(StoreCoursRequest $request)
    {       
        $item = $request->get('item');    
     
        if( $image = $request->file('item.icon') )
        {            
            $item['icon'] = Courses::saveIcon( $image );                 
        }

        $item['type'] = 'cours';

        $trainings = [];         
        for ($i=0; $i < $item['period']; $i++) { 
            $dayNumber = $i + 1;
            $trainings['day_'.$dayNumber.''] = [
                'item_id' => 0,
                'is_holiday' => false,
                'image' => 'no-image.png',
                'title' => ''
            ];                  
        }

        $item['training_schedule'] = $trainings;   
        $item['slug'] = str_slug($item['name']);
     
        $id_new_course = $this->courses->create($item);       

        Session::flash('success', 'Курс успешно создан!');
        return redirect()->route('course_trainings', ['id_course' => $id_new_course]);      
    }

    public function cours_edit($id)
    {
        $cours = $this->courses->find($id);
        if (!isset($cours))
        {
            return abort(404);
        }
        $title = 'Редактирование Курса';        
        $statuses = Courses::$coursStatuses;        

        return view('admin/pages/courses/new', compact(['title', 'statuses', 'cours']));
    }

    public function cours_update(CourseUpdateRequest $request, $id)
    {
        $course = $this->courses->find($id); 
        if (!isset($course))
        {
            return abort('404');
        }
        $newCourse = $request->get('item');
        $newPeriod = $newCourse['period'];
        $curPeriod = $course->period;
        $searchCourse = $this->courses->findWithParams(['name' => $newCourse['name']])->first(); 

        if(isset($searchCourse)) {
            if($searchCourse->id == $id) {                
                if($newPeriod != $curPeriod ) {
                    self::restoreCountTrainingsCourse($course, $curPeriod, $newPeriod);
                }  
                $course->update($newCourse);
                if( $icon = $request->file('item.icon') )
                {
                    File::delete( public_path('uploads/icons/'. $course->icon ));
                    $newIcon = Courses::saveIcon( $icon );
                    $course->update(['icon' => $newIcon]);       
                } 
                Session::flash('success', 'Курс успешно изменен!');
                return redirect()->back();
            } else {
                Session::flash('danger', 'Курс с таким Названием уже есть!');
                return redirect()->back();
            }
        } else {
            $newCourse['slug'] = str_slug($newCourse['name']);
            if($newPeriod != $curPeriod ) {
                self::restoreCountTrainingsCourse($course, $curPeriod, $newPeriod);
            }  
            $course->update($newCourse);
            if( $icon = $request->file('item.icon') )
            {
                File::delete( public_path('uploads/icons/'. $course->icon ));
                $newIcon = Courses::saveIcon( $icon );
                $course->update(['icon' => $newIcon]);       
            } 
            Session::flash('success', 'Курс успешно изменен!');
            return redirect()->back();
        }     
    }

    protected static function  restoreCountTrainingsCourse($course, $curPeriod, $newPeriod)
    {
        $training_schedule = $course->training_schedule;
        if($newPeriod > $curPeriod) {
            $indexPeriod = $newPeriod - $curPeriod;
            $countTrainings = count($training_schedule);
            
            for ($i=0; $i < $indexPeriod; $i++) { 
                $dayNumber = $countTrainings + ($i + 1);
                $training_schedule['day_'.$dayNumber.''] = [
                    'item_id' => 0,
                    'is_holiday' => false,
                    'image' => 'no-image.png',
                    'title' => ''
                ];                  
            }
            $course->update(['training_schedule' => $training_schedule]);

        } elseif($newPeriod < $curPeriod) {
            $indexPeriod = $curPeriod - $newPeriod;
            $countTrainings = count($training_schedule);
            $dayNumber = $countTrainings;
            for ($i=0; $i < $indexPeriod; $i++) {                 
                $popTraining = array_pop($training_schedule);                
                if($popTraining['item_id'] != 0) {
                    $id = $popTraining['item_id'];
                    $item = Item::find($id);
                    if (!isset($item))
                    {
                        return abort(404);
                    }
                    if( $item->image != 'no-image.png')
                    {
                        File::delete( public_path('uploads/items/'. $item->image ));           
                    }  
                    $item->users()->detach();
                    $item->delete();                    
                }
                $dayNumber = $countTrainings - $i;                 
            }
            $course->update(['training_schedule' => $training_schedule]);
            
        }

    }

    public function cours_destroy($id)
    {
        $cours = $this->courses->find($id);
        $itemsCours = $cours->items();       
        if($itemsCours->count() > 0) {
            Session::flash('danger', 'Курс не можит быть удален так как в нем есть тренировки и записи! Необходимо удалить или переназначить все записи в данном курсе или назначить для них другой курс!');
            return redirect()->route('show_courses');
        }
        if (!isset($cours)) {
            return abort('404');
        }
        $cours->delete();
        Session::flash('success', 'Курс успешно удален!');
        return redirect()->route('show_courses');
    }

    /*TRAININGS*/
    public function new_training($id_course, $numberDay)
    {
        $title = 'Создание тренировки';       
        $item = new Item;
        $statuses = Item::$ItemStatuses;
        $statusesDays = Item::$ItemTrainingStatuses; 
        $item['course_id'] = $id_course;
        $item['category_id'] = 1;                    

        return view('admin/pages/courses/new_trainings', compact(['title', 'item', 'statuses', 'numberDay', 'statusesDays']));
    }

    public function training_store(TrainingRequest $request)
    {        

        $item = $request->get('item'); 
        
        $searchItem = Item::where(['course_id' => $item['course_id']])->where(['title' => $item['title']])->exists();       
        if($searchItem) {
            Session::flash('danger', 'Тренировка с таким Названием в текущем Курсе уже есть!');
            return redirect()->back();
        } else {
            $numberDay = $request->get('numberDay'); 
            $item['slug'] = str_slug($item['title']);
            $item['number_day'] = $numberDay; 
            
            $course = $this->courses->find($item['course_id']);

            $training_schedule = $course->training_schedule;

            
            $training_image = 'no-image.png';
            if( $image = $request->file('item.image') )
            {            
                $item['image'] = Item::saveImage( $image );
                $training_image = $item['image'];                 
            }  

            // default value for item property is_active
            $item['is_active'] = true; 

           

            $newItem = $this->items->create($item);  

            $training_schedule['day_'.$numberDay] = [
                'item_id' => $newItem->id,
                'is_holiday' => $item['is_holiday'],  
                'image' => $training_image,   
                'title' => $item['title']       
            ];

            $course->update(['training_schedule' => $training_schedule]); 

            Session::flash('success', 'Тренировка успешно создана!');
            return redirect()->route('course_trainings', ['id_course' => $item['course_id']]);
        }
        
               
    }

    

    public function training_edit($id)
    {
        $item = $this->items->find($id);
        if (!isset($item))
        {
            return abort(404);
        }         
       
        $title = 'Редактирование тренировки';        
        $statuses = Item::$ItemStatuses;  
        $statusesDays = Item::$ItemTrainingStatuses;   
        $numberDay = $item->number_day;
        
        return view('admin/pages/courses/new_trainings', compact(['title', 'statuses', 'item', 'statusesDays', 'numberDay']));
    }

    public function training_update(TrainingUpdateRequest $request, $id) {
        $item = $this->items->find($id);      
        if (!isset($item))
        {
            return abort('404');
        }        

        $numberDay = $request->get('numberDay');
        $newItem = $request->get('item');

        $searchItem = Item::where(['course_id' => $newItem['course_id']])->where(['title' => $newItem['title']])->first();
        $course = $this->courses->find($item->course_id);
        $training_schedule = $course->training_schedule;

        if(isset($searchItem)) {
            if($searchItem->id == $id) {          
                $item->update($newItem);

                if( $image = $request->file('item.image') )
                {
                    File::delete( public_path('uploads/items/'. $item->image ));
                    $newImage = Item::saveImage( $image );
                    $item->update(['image' => $newImage]);       
                } 

                $training_schedule['day_'.$numberDay] = [
                    'item_id' => $item->id,
                    'is_holiday' => $item->is_holiday,  
                    'image' => $item->image,   
                    'title' => $item->title       
                ];    

                $course->update(['training_schedule' => $training_schedule]); 

                Session::flash('success', 'Тренировка успешно изменена!');
               /* return redirect()->route('course_trainings', ['id_course' => $item->course_id]); */
                return redirect()->back();
            } else {
                Session::flash('danger', 'Тренировка с таким Названием в текущем Курсе уже есть!');
                return redirect()->back();
            }
        } else {         
            $newItem['slug'] = str_slug($newItem['title']);
            $item->update($newItem);

            if( $image = $request->file('item.image') )
            {
                File::delete( public_path('uploads/items/'. $item->image ));
                $newImage = Item::saveImage( $image );
                $item->update(['image' => $newImage]);       
            }

            $training_schedule['day_'.$numberDay] = [
                'item_id' => $item->id,
                'is_holiday' => $item->is_holiday,  
                'image' => $item->image,   
                'title' => $item->title       
            ];    

            $course->update(['training_schedule' => $training_schedule]); 

            Session::flash('success', 'Тренировка успешно изменена!');
           /* return redirect()->route('course_trainings', ['id_course' => $item->course_id]); */
            return redirect()->back();
        }      
    }

    

    /*END TRAININGS*/

    

    // END COURSES

    // MARATHONES
    public function show_marathon($id)
    {
        $marathon = $this->courses->find($id);
        if (!isset($marathon))
        {
            return abort(404);
        }
        $title = $marathon->name;             

        return view('admin/pages/marathons/show', compact(['title', 'marathon']));
    }

    public function show_marathons() 
    {   
        $title = "Марафоны";        
        $marathons = $this->courses->findWithParams(['type' => 'marathon'])->get();      
        return view('admin/pages/marathons/index', compact(['title', 'marathons']));
    }

    public function marathons_filter()
    {
        
    }

    public function new_marathon()
    {        
        $title = 'Создание марафона';      
        $statuses = Courses::$coursStatuses;        
        return view('admin/pages/marathons/new', compact(['title', 'statuses']));
    }

    public function marathon_store(StoreMarathonsRequest $request)
    {
        $item = $request->get('item');    
     
        if( $image = $request->file('item.icon') )
        {            
            $item['icon'] = Courses::saveIcon( $image );                 
        }

        $item['type'] = 'marathon';

        $trainings = [];         
        for ($i=0; $i < $item['period']; $i++) { 
            $dayNumber = $i + 1;
            $trainings['day_'.$dayNumber.''] = [
                'item_id' => 0,
                'is_holiday' => false,
                'image' => 'no-image.png',
                'title' => ''
            ];                  
        }

        $item['training_schedule'] = $trainings;  
        $item['slug'] = str_slug($item['name']);

        $id_new_course = $this->courses->create($item);       

        Session::flash('success', 'Марафон успешно создан!');
        return redirect()->route('course_trainings', ['id_course' => $id_new_course]);
    }

    public function edit_marathon($id)
    {
        $marathon = $this->courses->find($id);
        if (!isset($marathon))
        {
            return abort(404);
        }
        $title = 'Редактирование Марафона';        
        $statuses = Courses::$coursStatuses;       

        return view('admin/pages/marathons/new', compact(['title', 'statuses', 'marathon']));
    }

    public function update_marathon(MarathonsUpdateRequest $request, $id)
    {
        $marathon = $this->courses->find($id);   
        if (!isset($marathon))
        {
            return abort('404');
        }

        $newMarathon = $request->get('item');
        $newPeriod = $newMarathon['period'];
        $curPeriod = $marathon->period;
        $searchMarathon = $this->courses->findWithParams(['name' => $newMarathon['name']])->first(); 

        if(isset($searchMarathon)) {
            if($searchMarathon->id == $id) {
                if($newPeriod != $curPeriod ) {
                    self::restoreCountTrainingsCourse($marathon, $curPeriod, $newPeriod);
                }  
                $marathon->update($newMarathon);
                if( $icon = $request->file('item.icon') )
                {
                    File::delete( public_path('uploads/icons/'. $course->icon ));
                    $newIcon = Courses::saveIcon( $icon );
                    $marathon->update(['icon' => $newIcon]);       
                } 
                Session::flash('success', 'Марафон успешно изменен!');
                return redirect()->back();
            } else {
                Session::flash('danger', 'Марафон с таким Названием уже есть!');
                return redirect()->back();
            }
        } else {
            $newMarathon['slug'] = str_slug($newMarathon['name']);
            if($newPeriod != $curPeriod ) {
                self::restoreCountTrainingsCourse($marathon, $curPeriod, $newPeriod);
            }  
            $marathon->update($newMarathon);
            if( $icon = $request->file('item.icon') )
            {
                File::delete( public_path('uploads/icons/'. $course->icon ));
                $newIcon = Courses::saveIcon( $icon );
                $marathon->update(['icon' => $newIcon]);       
            } 
            Session::flash('success', 'Марафон успешно изменен!');
            return redirect()->back();
        }     
    }

    public function marathon_destroy($id)
    {
        $marathon = $this->courses->find($id);
        $itemsMarathon = $marathon->items();          
        if(isset($itemsMarathon)) {
            if($itemsMarathon->count() > 0) {
                Session::flash('danger', 'Марафон не можит быть удалена так как в ней есть тренировки и записи! Необходимо удалить или переназначить все записи в данном марафон или назначить для них другой марафон!');
                return redirect()->route('show_marathons');
            }            
        }
        if (!isset($marathon)) {
            return abort('404');
        }
        $marathon->delete();
        Session::flash('success', 'Марафон успешно удален!');
        return redirect()->route('show_marathons');
    }
    // END MARATHONES
}
