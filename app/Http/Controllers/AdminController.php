<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingsRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\EditClientRequest;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreCoursRequest;
use App\Http\Requests\StoreMarathonsRequest;

use App\Repositories\UsersRepositoryInterface;
use App\Repositories\CategoriesRepositoryInterface;
use App\Repositories\ItemsRepositoryInterface;
use App\Repositories\ResultsRepositoryInterface;
use App\Repositories\CoursesRepositoryInterface;

use App\User;
use App\Settings;
use App\Social;
use App\Role;
use App\Category;
use App\Item;
use App\Result;
use App\Courses;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

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
        CoursesRepositoryInterface              $coursesRepository
             

    )
    {        
        $this->items = $itemsRepository;
        $this->users = $usersRepository;        
        $this->categories = $categoriesRepository;
        $this->courses = $coursesRepository;           
    }

    public function index()
    {
        $stats = [            
            'users' => $this->users->count()
        ];       

        return view('admin/index', compact(['stats']));
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
        return view('admin/pages/clients/show')->with(['title' => 'Профиль клиента: ' . $client->name, 'client' => $client]);
    }

    public function client_edit($id)
    {
        $client = $this->users->find($id);
        if (!isset($client)) {
            return abort(404);
        }        
        $roles = Role::all();
        $statuses = User::$userStatuses;
        return view('admin/pages/clients/new')->with([
            'title' => 'Редактирование клиента: ' . $client->name,
            'client' => $client,
            'roles' => $roles,
            'statuses' => $statuses
        ]);
    }

    public function client_update($id, EditClientRequest $request)
    {
        $client = $this->users->find($id);
        if (!isset($client)) {
            return abort(404);
        }
        $client->update($request->get('item'));
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
        return view('admin/pages/clients/new')->with([
            'title' => 'Coздание клиента',            
            'roles' => $roles,
            'statuses' => $statuses
        ]);
    }

     public function client_store(StoreClientRequest $request)
    {
        $settings = Settings::first(); 
        $item = $request->get('item');        
        $item['remember_token'] = $request->get('_token');       
        $newUserPass = str_random(8);
        $item['password'] = bcrypt($newUserPass);             
        $user = $this->users->create($item);        
        $user->roles()->attach([
            $item['role_id']
        ]);   

        mail($item['email'],
            "gizerskaya - Фитнесс Тренер",
            "Спасибо, что нас выбрали. \nВаши данные для входа в Ваш Личный Кабинет:\nЛогин: " . $item['email'] . "\nПароль: " . $newUserPass . "",
            "From:".$settings->email."\r\n"."X-Mailer: PHP/" . phpversion());

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
        $message = $request->get('messageUser');
        $userId = $request->get('user_id');
        $settings = Settings::first();                      
        $user = $this->users->find($userId); 

        $newUserPass = str_random(8);
        $hashNewPass = bcrypt($newUserPass); 
        $user->update(['password' => $hashNewPass]); 

        $currentMessage = "Ваш пароль обновлен. \nВаши данные для входа в Ваш Личный Кабинет:\nЛогин: " . $user->email . "\nПароль: " . $newUserPass . " ";     
        $currentMessage = $currentMessage . "\n" . $message;

        mail($user->email,
            "gizerskaya - Фитнесс Тренер",
            $currentMessage,
            "From:".$settings->email."\r\n"."X-Mailer: PHP/" . phpversion());

        Session::flash('success', 'Клиенту "'.$user->name.'" успешно отправленно сообщение и задан новый пароль!'); 

        return redirect()->back();
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

        return view('admin/pages/items/categories-new', compact(['title']));
    }

    public function categories_store(StoreCategoryRequest $request)
    {
        $item_request = $request->get('item');
        $category = $this->categories->create($item_request);       
        Session::flash('success', 'Категория успешно создана!');
        return redirect()->back();
    }

    public function edit_category($id)
    {
        $category = Category::find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $title = 'Изменение категории';        
        return view('admin/pages/items/categories-new', compact(['title', 'category']));
    }

    public function update_category($id, StoreCategoryRequest $request)
    {
        $category = $this->categories->find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $category->update($request->get('item')); 

        Session::flash('success', 'Категория успешно изменена!');
        return redirect()->back();
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
                    elseif ($key == 'course_id') {
                        $items->where('course_id',$filter);
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
        $item['slug'] = str_slug($item['title']);
        if( $image = $request->file('item.image') )
        {            
            $item['image'] = Item::saveImage( $image );                 
        }        

        $newItem = $this->items->create($item);       

        Session::flash('success', 'Запись успешно создана!');
        return redirect()->back();
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
        $statusesDays = Item::$ItemTrainingStatuses;
        $trainingSettings = Item::$TrainingSettings;
        $countWeek = $trainingSettings['maxWeek'];
        $countDay = $trainingSettings['maxDay'];

        return view('admin/pages/items/new', compact(['title', 'categories', 'statuses', 'item','statusesDays', 'countWeek', 'countDay']));
    }

    public function item_update(ItemRequest $request, $id)
    {       

        $item = $this->items->find($id);
        if (!isset($item))
        {
            return abort('404');
        }
        $item->update($request->get('item'));

        if( $image = $request->file('item.image') )
        {
            File::delete( public_path('uploads/items/'. $item->image ));
            $newImage = Item::saveImage( $image );
            $item->update(['image' => $newImage]);       
        }        

        Session::flash('success', 'Запись успешно изменена!');
        return redirect()->back();
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
            
        $trainings = $curentCourse->training_schedule;
       
        $title = 'Создание тренировок';     
            
        return view('admin/pages/courses/trainings', compact(['title', 'trainings', 'id_course']));
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
     
        $id_new_course = $this->courses->create($item);       

        Session::flash('success', 'Курс успешно создан!');
        return redirect()->route('course_trainings', ['id_course' => $id_new_course]);      
    }

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

    public function training_store(ItemRequest $request)
    {
        $numberDay = $request->get('numberDay');       
        $item = $request->get('item'); 
        $item['slug'] = str_slug($item['title']);
        
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

    

    public function training_edit($id)
    {
        $item = $this->items->find($id);
        if (!isset($item))
        {
            return abort(404);
        }
      
        $course = $this->courses->find($item->course_id);
        $training_schedule = $course->training_schedule;
        $numberDay = '';
        foreach ($training_schedule as $key => $value) {
            if($value['item_id'] == $id) {
                $numberDay = str_replace('day_','',$key);
            }
        }
        $title = 'Редактирование тренировки';        
        $statuses = Item::$ItemStatuses;  
        $statusesDays = Item::$ItemTrainingStatuses;   
        
        return view('admin/pages/courses/new_trainings', compact(['title', 'statuses', 'item', 'statusesDays', 'numberDay']));
    }

    public function training_update(ItemRequest $request, $id) {
        $item = $this->items->find($id);
        $numberDay = $request->get('numberDay');
        if (!isset($item))
        {
            return abort('404');
        }
        $course = $this->courses->find($item->course_id);
        $training_schedule = $course->training_schedule;
        $item->update($request->get('item'));

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
        return redirect()->route('course_trainings', ['id_course' => $item->course_id]); 
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

    public function cours_update(StoreCoursRequest $request, $id)
    {
        $cours = $this->courses->find($id);   
        if (!isset($cours))
        {
            return abort('404');
        }
        $cours->update($request->get('item'));
     
        if( $icon = $request->file('item.icon') )
        {
            File::delete( public_path('uploads/icons/'. $cours->icon ));
            $newIcon = Courses::saveIcon( $icon );
            $cours->update(['icon' => $newIcon]);       
        }          

        Session::flash('success', 'Курс успешно изменен!');
        return redirect()->back();
    }

    public function cours_destroy($id)
    {
        $cours = $this->courses->find($id);
        $itemsCours = $cours->items();       
        if($itemsCours->count() > 0) {
            Session::flash('danger', 'Курс не можит быть удалена так как в ней есть тренировки и записи! Необходимо удалить или переназначить все записи в данном курсе или назначить для них другой курс!');
            return redirect()->route('show_courses');
        }
        if (!isset($cours)) {
            return abort('404');
        }
        $cours->delete();
        Session::flash('success', 'Курс успешно удален!');
        return redirect()->route('show_courses');
    }

    

    // END COURSES

    // MARATHONES
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
        $this->courses->create($item);        

        Session::flash('success', 'Марафон успешно создана!');
        return redirect()->back();
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

    public function update_marathon(StoreMarathonsRequest $request, $id)
    {
        $marathon = $this->courses->find($id);   
        if (!isset($marathon))
        {
            return abort('404');
        }
        $marathon->update($request->get('item'));
     
        if( $icon = $request->file('item.icon') )
        {
            File::delete( public_path('uploads/icons/'. $cours->icon ));
            $newIcon = Courses::saveIcon( $icon );
            $marathon->update(['icon' => $newIcon]);       
        }          

        Session::flash('success', 'Марафон успешно изменен!');
        return redirect()->back();
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
