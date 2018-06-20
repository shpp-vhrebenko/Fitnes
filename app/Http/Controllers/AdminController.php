<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingsRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\EditClientRequest;

use App\Http\Requests\ItemRequest;
use App\Http\Requests\StoreCategoryRequest;

use App\Repositories\UsersRepositoryInterface;
use App\Repositories\CategoriesRepositoryInterface;
use App\Repositories\ItemsRepositoryInterface;
use App\Repositories\ResultsRepositoryInterface;

use App\User;
use App\Settings;
use App\Social;
use App\Role;
use App\Category;
use App\Item;
use App\Result;

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
         ResultsRepositoryInterface              $resultsRepository       

    )
    {        
        $this->items = $itemsRepository;
        $this->users = $usersRepository;        
        $this->categories = $categoriesRepository;
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
            $clients = $this->users->all();
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
        $client->roles()->detach([
            $client['role_id']
        ]);  
        if (!isset($client)) {
            return abort(404);
        }
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
                        $clients->where('name', 'like', '%'. $filter .'%');
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

            foreach ($filter_request as $key => $p_f)
            {
                if ($p_f != null)
                {
                    $paginate->appends('filter[' . $key . ']', $p_f);
                }
            }

            $this->setTitle('Фильтр клиентов');

            return view('admin/pages/clients/index')->with(['clients' => $paginate]);
        } else {
            return redirect()->back();
        }
    }

    public function client_sendMessage(Request $request) {
        $message = $request->get('messageUser');
        $userId = $request->get('user_id');
        $settings = Settings::first();                      
        $user = $this->users->find($userId);        

        mail($user->email,
            "gizerskaya - Фитнесс Тренер",
            $message,
            "From:".$settings->email."\r\n"."X-Mailer: PHP/" . phpversion());

        Session::flash('success', 'Клиенту "'.$user->name.'" успешно отправленно сообщение!'); 

        return redirect()->back();
    }

    // END CLIENTS    

    // CATEGORY
     public function categories()
    {
        $title = 'Список категорий';
        $categories = $this->categories->all();
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
        if($itemsCategory->count() > 0) {
            Session::flash('danger', 'Категория не можит быть удалена так как в ней есть записи! Необходимо удалить все записи данной категории или назначить для них другую категорию!');
            return redirect()->route('categories');
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
            $items = $this->items->order('category_id', 'asc')->paginate(10);
            $items->load('category');
            return view('admin/pages/items/items', compact(['title', 'items']));
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
                    elseif ($key == 'name') {
                        $items->where('name', 'like', '%'. $filter .'%');
                    }
                    elseif ($key == 'price_min') {
                        $items->where('price', '>=', $filter);
                    }
                    elseif ($key == 'price_max') {
                        $items->where('price', '<=', $filter);
                    }
                    elseif ($key == 'qty_min') {
                        $items->where('qty', '>=', $filter);
                    }
                    elseif ($key == 'qty_max') {
                        $items->where('qty', '<=', $filter);
                    }
                    elseif ($key == 'company') {
                        $items->where('company', 'like', '%'. $filter .'%');
                    }
                    elseif ($key == 'role_id') {
                        $items->whereHas('roles', function ($q) use ($filter) {
                            $q->where('roles.id', $filter);
                        });
                    }
                    else {
                        $items->where($key, $filter);
                    }
                }
            }
//            dd($orders);
            $paginate = $items->paginate(10);

            foreach ($filter_request as $key => $p_f)
            {
                if ($p_f != null)
                {
                    $paginate->appends('filter[' . $key . ']', $p_f);
                }
            }

            $this->setTitle('Фильтр товаров');
            return view('admin/pages/items/items')->with(['items' => $paginate]);
        } else {
            return redirect()->back();
        }
    }

    public function items_new()
    {
        $title = 'Создание записи';
        $categories = $this->categories->all();   
        $statuses = Item::$ItemStatuses;
        $statusesDays = Item::$ItemTrainingStatuses;
        $trainingSettings = Item::$TrainingSettings;
        $countWeek = $trainingSettings['maxWeek'];
        $countDay = $trainingSettings['maxDay'];

        return view('admin/pages/items/new', compact(['title', 'categories', 'statuses', 'statusesDays', 'countWeek', 'countDay']));
    }

      public function items_store(ItemRequest $request)
    {

        $item = $request->get('item');    
     
        if( $image = $request->file('item.image') )
        {            
            $item['image'] = Item::saveImage( $image );                 
        }

        $this->items->create($item);        

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
        $item->delete();
        Session::flash('success', 'Товар успешно удален!');
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
}
