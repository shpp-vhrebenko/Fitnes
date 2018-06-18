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

use App\User;
use App\Settings;
use App\Social;
use App\Role;
use App\Category;
use App\Item;

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
        CategoriesRepositoryInterface           $categoriesRepository     

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

    public function categories_new($id = null)
    {
        $title = 'Создание категории';
        $current_category = $id;       
        $count_categories = Category::all()->count();
        //$categories = $this->item_categories->all()->load('subcategories');
        return view('admin/pages/items/categories-new', compact(['title', 'categories', 'count_categories', 'current_category']));
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
        if (!isset($category))
        {
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

        return view('admin/pages/items/new', compact(['title', 'categories']));
    }

      public function items_store(ItemRequest $request)
    {
        $item_request = $request->get('item');
        $item_request_preview = $request->file('item.preview');
        if (isset($item_request_preview)) {
            $preview_id = $this->imageUpload($request, 'item.preview')->id;
            $item_request['preview_id'] = $preview_id;
        }
        $i = $this->items->create($item_request);

        if (isset($i)) {

            for ($j = 1; $j <= 15; $j++)
            {
                $image_gallery = $request->file('photo_' . $j);
                if (isset($image_gallery))
                {
                    $this->imageGalleryUpload($request, 'photo_' . $j, $j, $i->id);
                }
            }

        }


        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('i-' . $i->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['item_id'] = $i->id;
                    $item['locale'] = $key;
                    ItemTranslation::create($item);
                }
            }
        }

        if ($request->get('categories')) {
            $categories = $request->get('categories');
            if (isset($categories)) {
                foreach ($categories as $category)
                {
                    ItemMultiCategory::create([
                        'item_id' => $i->id,
                        'category_id' => $category
                    ]);
                }
            }
        }

        if ($request->get('items_related')) {
            $items_related = $request->get('items_related');
            if (isset($items_related) && isset($i)) {
                foreach ($items_related as $item_rel)
                {
                    RecommendedItem::create([
                        'item_id' => $i->id,
                        'recommended_id' => $item_rel
                    ]);
                }
            }
        }

        if ($request->get('terms')) {
            $terms = $request->get('terms');
            if (isset($terms)) {
                foreach ($terms as $term)
                {
                    ItemTerms::create([
                        'item_id' => $i->id,
                        'term_id' => $term
                    ]);
                }
            }
        }

        if ($request->get('characteristics')) {
            $characteristics = $request->get('characteristics');
            if (isset($characteristics)) {
                foreach ($characteristics as $key => $characteristic)
                {
                    foreach ($characteristic as $key2 => $item){
                        if (isset($item)) {
                            CharacteristicsChildTranslations::create([
                                'item_id' => $i->id,
                                'ch_id' => $key,
                                'locale' => $key2,
                                //'value' => serialize(str_replace(' ', '', $item))
                                'value' => serialize($item)
                            ]);
                        }
                    }
                }
            }
        }

        if ($request->get('technologies')) {
            $technologies = $request->get('technologies');
            if (isset($technologies)) {
                foreach ($technologies as $technology)
                {
                    ItemTechnology::create([
                        'item_id' => $i->id,
                        'technology_id' => $technology
                    ]);
                }
            }
        }

        if ($request->get('table_size')) {
            $table_size = $request->get('table_size');
            if (isset($table_size)) {
                ItemTableSize::create([
                    'item_id' => $i->id,
                    'table_id' => $table_size
                ]);
            }
        }

        Session::flash('success', 'Товар успешно создан!');
        return redirect()->back();
    }

    public function item_edit($id)
    {
        $item = $this->items->find($id);
        if (!isset($item))
        {
            return abort(404);
        }
        $this->setTitle('Редактирование товара');

        $categories = ItemCategory::with(['locales', 'subcategories.locales', 'subcategories.subcategories.locales'])->get();
        //$categories = $this->item_categories->all()->load('subcategories');

        $attributes = ItemAttribute::whereHas('terms_list', function ($query) {
            $query->where('is_active', true);
        })->with(['locales', 'terms_list.locales'])->get();
        //$attributes = $this->item_attributes->all();

        $characteristics = ItemCharacteristic::where('is_active', true)->get();
        $characteristics->load('locales');

        $table_size = TableSize::all();
        $table_size->load('locales');

        $technologies = Technology::all();
        $technologies->load('locales');

        $items_related = Item::where('id', '!=', $item->id)->get();

        $technologies_exist = [];
        $itech = ItemTechnology::where('item_id', $item->id)->get();
        foreach ($itech as $v)
        {
            array_push($technologies_exist, $v->technology->id);
        }
        return view('admin/pages/items/new')->with(['categories' => $categories, 'item' => $item, 'attributes' => $attributes, 'characteristics' => $characteristics, 'table_size' => $table_size, 'technologies' => $technologies, 'technologies_exist' => $technologies_exist, 'items_related' => $items_related]);
    }

    public function item_update(ItemRequest $request, $id)
    {

        //dd($request->all());

        $item = $this->items->find($id);
        if (!isset($item))
        {
            return abort('404');
        }
        $item->update($request->get('item'));
        $item_locales = ItemTranslation::where('item_id', $id)->get();
        if (isset($item_locales) && count($item_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $iteml)
                {
                    if ($item_locales[$index]->locale == $key)
                    {
                        $item_locales[$index]->update($iteml);
                    }
                    $index++;
                }
            }
        }

        if ($request->get('categories'))
        {
            $categories = $request->get('categories');
            ItemMultiCategory::where('item_id', $item->id)->delete();
            foreach ($categories as $category)
            {
                if (isset($category) && $category != null)
                {
                    ItemMultiCategory::create([
                        'item_id' => $item->id,
                        'category_id' => $category
                    ]);
                }
            }
        }

        if ($request->get('terms'))
        {
            $terms_arr = $request->get('terms');
            ItemTerms::where('item_id', $item->id)->delete();
            foreach ($terms_arr as $t_arr)
            {
                if (isset($t_arr) && $t_arr != null) {
                    ItemTerms::create([
                        'item_id' => $item->id,
                        'term_id' => $t_arr
                    ]);
                }
            }
        }

        if ($request->get('items_related'))
        {
            $items_related_arr = $request->get('items_related');
            RecommendedItem::where('item_id', $item->id)->delete();
            foreach ($items_related_arr as $item_r_arr)
            {
                if (isset($item_r_arr) && $item_r_arr != null) {
                    RecommendedItem::create([
                        'item_id' => $item->id,
                        'recommended_id' => $item_r_arr
                    ]);
                }
            }
        }

        if ($request->get('technologies'))
        {
            $technologies_arr = $request->get('technologies');
            ItemTechnology::where('item_id', $item->id)->delete();
            foreach ($technologies_arr as $technology)
            {
                if (isset($technology) && $technology != null) {
                    ItemTechnology::create([
                        'item_id' => $item->id,
                        'technology_id' => $technology
                    ]);
                }
            }
        }

        if ($request->get('item_ch'))
        {
            $item_ch = $request->get('item_ch');

            if (isset($item_ch)) {
                foreach ($item_ch as $key => $c)
                {
                    if (isset($c) && $c != null) {
                        $ct = CharacteristicsChildTranslations::where('id', $key);
                        if ($ct->first()['value'] != serialize($c)) {
                            CharacteristicsChildTranslations::where('id', $key)->update([
                                'value' => serialize($c)
                            ]);
                        }
                    } else {
                        CharacteristicsChildTranslations::where('id', $key)->delete();
                    }
                }
            }
        }

        if ($request->get('characteristics')) {
            $characteristics = $request->get('characteristics');

            if (isset($characteristics)) {
                foreach ($characteristics as $key => $characteristic)
                {
                    foreach ($characteristic as $key2 => $itemz){
                        if (isset($itemz)) {
                            CharacteristicsChildTranslations::create([
                                'item_id' => $item->id,
                                'ch_id' => $key,
                                'locale' => $key2,
                                'value' => serialize(str_replace(' ', '', $itemz))
                            ]);
                        }
                    }
                }
            }
        }


        $item_request_preview = $request->file('item.preview');
        if (isset($item_request_preview)) {
            $preview_id = $this->imageUpload($request, 'item.preview')->id;

            if (isset($preview_id)) {
                if (isset($item->preview_id)) {
                    $img = Image::find($item->preview_id);
                    File::delete(public_path() . $img->path);
                    $img->delete();
                }
            }

            $item->update([
                'preview_id' => $preview_id
            ]);
        }

        if (isset($item)) {

            for ($j = 1; $j <= 15; $j++)
            {
                $image_gallery = $request->file('photo_' . $j);
                if (isset($image_gallery))
                {
                    $this->imageGalleryUpload($request, 'photo_' . $j, $j, $item->id);
                }
            }

        }


        if ($request->get('table_size')) {
            $table_size = $request->get('table_size');

            if (isset($item->table_size) && count($item->table_size) && $item->table_size[0]->id != $table_size)
            {
                ItemTableSize::where('item_id', $item->id)->update(['table_id' => $table_size]);
            } else {
                ItemTableSize::create([
                    'item_id' => $item->id,
                    'table_id' => $table_size
                ]);
            }

        }

        if (!$request->get('table_size') && isset($item->table_size)) {
            ItemTableSize::where('item_id', $item->id)->delete();
        }

        Session::flash('success', 'Товар успешно изменен!');
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
}
