<?php

namespace App\Http\Controllers;


use App\Repositories\UsersRepositoryInterface;
use App\User;
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
        UsersRepositoryInterface                    $usersRepository      
    )
    {        
        $this->users = $usersRepository;        
    }

    public function index()
    {
        $stats = [            
            'users' => $this->users->count()
        ];       

        return view('admin/index')->with([   
            'stats' => $stats         
        ]);
    }










    public function items_categories()
    {
        $this->setTitle('Список родительских категорий');
        $categories = $this->item_categories->order('sort_order', 'asc')->get()->load('locales');
        return view('admin/pages/items/categories')->with(['categories' => $categories]);
    }

    public function categories_new($id = null)
    {
        $this->setTitle('Создание категории');
        $categories = ItemCategory::with(['subcategories.locales', 'subcategories.subcategories.locales'])->where('is_active', true)->get();
        $count_categories = ItemCategory::all()->count();
        //$categories = $this->item_categories->all()->load('subcategories');
        return view('admin/pages/items/categories-new')->with(['categories' => $categories, 'count_categories' => $count_categories, 'current_category' => $id]);
    }

    public function categories_store(StoreItemCategoryRequest $request)
    {
        $item_request = $request->get('item');
        $item_request_preview = $request->file('item.preview');
        if (isset($item_request_preview)) {
            $preview_id = $this->imageUpload($request, 'item.preview')->id;
            $item_request['preview_id'] = $preview_id;
        }

        $item_request_poster = $request->file('item.poster');
        if (isset($item_request_poster)) {
            $poster_id = $this->imageUpload($request, 'item.poster')->id;
            $item_request['poster_id'] = $poster_id;
        }

        $category = $this->item_categories->create($item_request);
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('c-' . $category->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['category_id'] = $category->id;
                    $item['locale'] = $key;
                    ItemCategoriesTranslation::create($item);
                }
            }
        }
        Session::flash('success', 'Категория успешно создана!');
        return redirect()->back();
    }

    public function edit_items_category($id)
    {
        $category = ItemCategory::find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $this->setTitle('Изменение категории');
        $categories = ItemCategory::with(['subcategories.locales', 'subcategories.subcategories.locales'])->where('is_active', true)->get();
        //$categories = $this->item_categories->all()->load('subcategories');
        return view('admin/pages/items/categories-new')->with(['categories' => $categories, 'category' => $category]);
    }

    public function update_items_category($id, StoreItemCategoryRequest $request)
    {
        $category = $this->item_categories->find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $category->update($request->get('item'));
        $category_locales = ItemCategoriesTranslation::where('category_id', $id)->get();
        if (isset($category_locales) && count($category_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $item)
                {
                    if ($category_locales[$index]->locale == $key)
                    {
                        $category_locales[$index]->update($item);
                    }
                    $index++;
                }
            }
        }

        $item_request_preview = $request->file('item.preview');
        if (isset($item_request_preview)) {
            $preview_id = $this->imageUpload($request, 'item.preview')->id;
            $category->update(['preview_id' => $preview_id]);
        }

        $item_request_poster = $request->file('item.poster');
        if (isset($item_request_poster)) {
            $poster_id = $this->imageUpload($request, 'item.poster')->id;
            $category->update(['poster_id' => $poster_id]);
        }

        Session::flash('success', 'Категория успешно изменена!');
        return redirect()->back();
    }

    public function destroy_items_category($id)
    {
        $category = $this->item_categories->find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $category->delete();
        Session::flash('success', 'Категория успешно удалена!');
        return redirect()->route('admin_items_categories');
    }

    public function items_category_subcategories($id)
    {
        $category = $this->item_categories->find($id)->load('locales');
        if (!isset($category))
        {
            return abort('404');
        }
        $this->setTitle('Список подкатегорий: ' . $category->locales[0]->name);
        $categories = $this->item_categories->findWithParams(['parent_id' => $category->id])->orderBy('sort_order', 'asc')->paginate(10);
        $categories->load('locales');
        return view('admin/pages/items/subcategories')->with(['categories' => $categories, 'parent_cat_id' => $id]);
    }










    public function items()
    {
            $this->setTitle('Список товаров');
            $items = $this->items->order('id', 'desc')->paginate(10);
            $items->load(['categories','categories.locales']);
            return view('admin/pages/items/items')->with(['items' => $items]);
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
        $this->setTitle('Создание товара');


        $categories = ItemCategory::with(['subcategories.locales', 'subcategories.subcategories.locales'])->where('is_active', true)->get();
        //$categories = $this->item_categories->all()->load('subcategories');

        $attributes = ItemAttribute::whereHas('terms_list', function ($query) {
            $query->where('is_active', true);
        })->with('terms_list.locales')->get();
        //$attributes = $this->item_attributes->all();

        $characteristics = ItemCharacteristic::where('is_active', true)->get();
        $characteristics->load('locales');

        $table_size = TableSize::all();
        $table_size->load('locales');

        $technologies = Technology::all();
        $technologies->load('locales');

        $items_related = $this->items->all();

        return view('admin/pages/items/new')->with(['categories' => $categories, 'attributes' => $attributes, 'characteristics' => $characteristics, 'table_size' => $table_size, 'technologies' => $technologies, 'items_related' => $items_related]);
    }

    private function imageUpload(Request $request, $name) {
        $this->validate($request, [
            $name => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4192',
        ]);

        if ($request->hasFile($name)) {
            $image = $request->file($name);
            $name = $this->genRandomString().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/' . Carbon::now()->format('d-m-Y') . '/');
            $image->move($destinationPath, $name);
            //$this->save();

            $image_model = Image::create(['path' => '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name]);

            return $image_model;
        }
    }

    private function imageGalleryUpload(Request $request, $name, $position, $id, $status = true) {
        $this->validate($request, [
            $name => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4192',
        ]);

        if ($request->hasFile($name)) {
            $image = $request->file($name);
            $name = $this->genRandomString().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/' . Carbon::now()->format('d-m-Y') . '/');
            $image->move($destinationPath, $name);
            //$this->save();
            if ($status) {

                $check = ItemGallery::where([['item_id', $id], ['position', $position]]);
                if (isset($check) && $check != null && $check->count() > 0)
                {
                    $image_model = $check->update(['path' => '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name]);
                } else {
                    $image_model = ItemGallery::create(['item_id' => $id, 'position' => $position, 'path' => '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name]);
                }

                return $image_model;
            } else {
                return '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name;
            }
        }
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

    public function preview_destroy($id)
    {
        Item::find($id)->update(['preview_id' => null]);
        return response()->json(true);
    }

    public function preview_destroy_item_cat($id)
    {
        ItemCategory::find($id)->update(['preview_id' => null]);
        return response()->json(true);
    }

    public function poster_destroy_item_cat($id)
    {
        ItemCategory::find($id)->update(['poster_id' => null]);
        return response()->json(true);
    }

    public function preview_destroy_article($id)
    {
        BlogArticle::find($id)->update(['preview_id' => null]);
        return response()->json(true);
    }

    public function preview_destroy_technology($id)
    {
        Technology::find($id)->update(['preview_id' => null]);
        return response()->json(true);
    }

    public function gallery_destroy($id)
    {
        ItemGallery::find($id)->delete();
        return response()->json(true);
    }

    public function article_gallery_destroy($id)
    {
        BlogArticleGallery::find($id)->delete();
        return response()->json(true);
    }

    public function technology_gallery_destroy($id)
    {
        TechnologyGallery::find($id)->delete();
        return response()->json(true);
    }

    public function slide_preview_destroy($id)
    {
        Slide::find($id)->update(['attach_id' => null]);
        return response()->json(true);
    }









    public function items_attributes()
    {
        $this->setTitle('Список атрибутов');
        $attributes = $this->item_attributes->order('id', 'desc')->paginate(10);
        return view('admin/pages/items/attributes')->with(['attributes' => $attributes]);
    }

    public function attributes_new()
    {
        $this->setTitle('Создание атрибута');
        $categories = ItemCategory::with(['subcategories.locales', 'subcategories.subcategories.locales'])->where('is_active', true)->get();
        //$categories = $this->item_categories->all()->load('subcategories');
        return view('admin/pages/items/attributes-new')->with(['categories' => $categories]);
    }

    public function attributes_store(StoreItemAttributeRequest $request)
    {
        $attributes = $this->item_attributes->create($request->get('item'));
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('a-' . $attributes->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['attribute_id'] = $attributes->id;
                    $item['locale'] = $key;
                    ItemAttributesTranslation::create($item);
                }
            }
        }
        Session::flash('success', 'Атрибут успешно создан!');
        return redirect()->back();
    }

    public function edit_attribute($id)
    {
        $attribute = $this->item_attributes->find($id);
        if (!isset($attribute)) {
            return abort(404);
        }
        $this->setTitle('Изменение атрибута');
        $categories = ItemCategory::with(['subcategories.locales', 'subcategories.subcategories.locales'])->where('is_active', true)->get();
        //$categories = $this->item_categories->all()->load('subcategories');
        return view('admin/pages/items/attributes-new')->with(['categories' => $categories, 'attribute' => $attribute]);
    }

    public function update_attribute($id, StoreItemAttributeRequest $request)
    {
        $attribute = $this->item_attributes->find($id);
        if (!isset($attribute))
        {
            return abort('404');
        }
        $attribute->update($request->get('item'));
        $attribute_locales = ItemAttributesTranslation::where('attribute_id', $id)->get();
        if (isset($attribute_locales) && count($attribute_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $item)
                {
                    if ($attribute_locales[$index]->locale == $key)
                    {
                        $attribute_locales[$index]->update($item);
                    }
                    $index++;
                }
            }
        }
        Session::flash('success', 'Атрибут успешно изменен!');
        return redirect()->back();
    }

    public function destroy_attribute($id)
    {
        $attribute = $this->item_attributes->find($id);
        if (!isset($attribute))
        {
            return abort('404');
        }
        $attribute->delete();
        Session::flash('success', 'Атрибут успешно удален!');
        return redirect()->route('admin_items_attributes');
    }









    public function attribute_terms($id)
    {
        $attribute = $this->item_attributes->find($id);
        if (!isset($attribute)) {
            return abort(404);
        }
        $this->setTitle('Опции атрибута: ' . $attribute->locales[0]->name);
        return view('admin/pages/items/attribute')->with(['attribute' => $attribute, 'terms' => $attribute->terms()]);
    }

    public function attribute_terms_new($id)
    {
        $attribute = $this->item_attributes->find($id);
        if (!isset($attribute)) {
            return abort(404);
        }
        $this->setTitle('Создание опции');
        $attributes = $this->item_attributes->all();
        return view('admin/pages/items/terms-new')->with(['attribute' => $attribute, 'attributes' => $attributes]);
    }

    public function attribute_terms_store(StoreItemTermRequest $request)
    {
        $terms = $this->attribute_terms->create($request->get('item'));
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('t-' . $terms->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['term_id'] = $terms->id;
                    $item['locale'] = $key;
                    AttributeTermsTranslation::create($item);
                }
            }
        }
        Session::flash('success', 'Опция успешно создана!');
        return redirect()->back();
    }

    public function edit_term($attr_id, $term_id)
    {
        $term = $this->attribute_terms->find($term_id);
        if (!isset($term)) {
            return abort(404);
        }
        $this->setTitle('Редактирование опции: ' . $term->locales[0]->name);
        $attributes = $this->item_attributes->all();
        $attribute = $this->item_attributes->find($attr_id);
        return view('admin/pages/items/terms-new')->with(['attribute' => $attribute, 'attributes' => $attributes, 'term' => $term]);
    }

    public function update_term($attr_id, $term_id, StoreItemTermRequest $request)
    {
        $term = $this->attribute_terms->find($term_id);
        if (!isset($term))
        {
            return abort('404');
        }
        $term->update($request->get('item'));
        $term_locales = AttributeTermsTranslation::where('term_id', $term_id)->get();
        if (isset($term_locales) && count($term_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $item)
                {
                    if ($term_locales[$index]->locale == $key)
                    {
                        $term_locales[$index]->update($item);
                    }
                    $index++;
                }
            }
        }
        Session::flash('success', 'Опция успешно изменена!');
        return redirect()->back();
    }

    public function destroy_term($attr_id, $term_id)
    {
        $term = $this->attribute_terms->find($term_id);
        if (!isset($term))
        {
            return abort('404');
        }
        $term->delete();
        Session::flash('success', 'Опция успешно удалена!');
        return redirect()->route('show_attribute', $attr_id);
    }







    public function items_characteristics()
    {
        $this->setTitle('Список характеристик');
        $characteristics = $this->item_characteristics->order('id', 'desc')->paginate(10);
        return view('admin/pages/items/characteristics')->with(['characteristics' => $characteristics]);
    }

    public function characteristics_new()
    {
        $this->setTitle('Создание характеристики');
        return view('admin/pages/items/characteristics-new');
    }

    public function characteristics_store(StoreItemCharacteristicRequest $request)
    {
        $characteristics = $this->item_characteristics->create($request->get('item'));
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('ch-' . $characteristics->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['ch_id'] = $characteristics->id;
                    $item['locale'] = $key;
                    ItemCharacteristicsTranslation::create($item);
                }
            }
        }
        Session::flash('success', 'Характеристика успешно создана!');
        return redirect()->back();
    }

    public function edit_characteristic($id)
    {
        $characteristics = $this->item_characteristics->find($id);
        if (!isset($characteristics)) {
            return abort('404');
        }
        $this->setTitle('Просмотр характеристики');
        return view('admin/pages/items/characteristics-new')->with(['characteristics' => $characteristics]);
    }

    public function update_characteristic($id, StoreItemCharacteristicRequest $request)
    {
        $characteristics = $this->item_characteristics->find($id);
        if (!isset($characteristics)) {
            return abort('404');
        }

        $characteristics->update($request->get('item'));
        $characteristics_locales = ItemCharacteristicsTranslation::where('ch_id', $id)->get();
        if (isset($characteristics_locales) && count($characteristics_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $item)
                {
                    if ($characteristics_locales[$index]->locale == $key)
                    {
                        $characteristics_locales[$index]->update($item);
                    }
                    $index++;
                }
            }
        }
        Session::flash('success', 'Характеристика успешно изменена!');
        return redirect()->back();
    }

    public function destroy_characteristic($id)
    {
        $characteristics = $this->item_characteristics->find($id);
        if (!isset($characteristics))
        {
            return abort('404');
        }
        $characteristics->delete();
        Session::flash('success', 'Характеристика успешно удалена!');
        return redirect()->route('admin_items_characteristics');
    }









    public function technologies(){
        $this->setTitle('Список технологий');
        $technologies = Technology::paginate(10);
        return view('admin/pages/technologies/index')->with(['technologies' => $technologies]);
    }
    public function technology_new(){
        $this->setTitle('Создание технологии');
        $categories = TechnologiesCategory::all();
        return view('admin/pages/technologies/new')->with(['categories' => $categories]);
    }
    public function technology_store(StoreTechnologyRequest $request){
        $item_request = $request->get('item');
        $item_request_preview = $request->file('item.preview');

        if (isset($item_request_preview)) {
            $preview_id = $this->imageUpload($request, 'item.preview')->id;
            $item_request['preview_id'] = $preview_id;
        }

        $technology = Technology::create($item_request);
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('th-' . $technology->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['technology_id'] = $technology->id;
                    $item['locale'] = $key;
                    TechnologiesTranslation::create($item);
                }
            }
        }

        if (isset($technology)) {

            for ($j = 1; $j <= 5; $j++)
            {
                $image_gallery = $request->file('photo_' . $j);
                if (isset($image_gallery))
                {
                    $this->imageTechnologyGalleryUpload($request, 'photo_' . $j, $j, $technology->id);
                }
            }

        }

        Session::flash('success', 'Технология успешно создана!');
        return redirect()->back();
    }

    private function imageTechnologyGalleryUpload(Request $request, $name, $position, $id, $status = true) {
        $this->validate($request, [
            $name => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4192',
        ]);

        if ($request->hasFile($name)) {
            $image = $request->file($name);
            $name = $this->genRandomString().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/' . Carbon::now()->format('d-m-Y') . '/');
            $image->move($destinationPath, $name);
            //$this->save();
            if ($status) {

                $check = TechnologyGallery::where([['technology_id', $id], ['position', $position]]);
                if (isset($check) && $check != null && $check->count() > 0)
                {
                    $image_model = $check->update(['path' => '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name]);
                } else {
                    $image_model = TechnologyGallery::create(['technology_id' => $id, 'position' => $position, 'path' => '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name]);
                }
                return $image_model;
            } else {
                return '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name;
            }
        }
    }

    public function technology_edit($id){
        $technology = Technology::find($id);
        if (!isset($technology)) {
            return abort('404');
        }

        $this->setTitle('Изменение технологии');
        $categories = TechnologiesCategory::all();
        return view('admin/pages/technologies/new')->with(['item' => $technology, 'categories' => $categories]);
    }
    public function technology_update($id, StoreTechnologyRequest $request){
        //dd($request->all());
        $technology = Technology::find($id);
        if (!isset($technology)) {
            return abort('404');
        }

        $technology->update($request->get('item'));
        $technology_locales = TechnologiesTranslation::where('technology_id', $id)->get();
        if (isset($technology_locales) && count($technology_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $item)
                {
                    if ($technology_locales[$index]->locale == $key)
                    {
                        $technology_locales[$index]->update($item);
                    }
                    $index++;
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

            $technology->update([
                'preview_id' => $preview_id
            ]);
        }


        if (isset($technology)) {

            for ($j = 1; $j <= 5; $j++)
            {
                $image_gallery = $request->file('photo_' . $j);
                if (isset($image_gallery))
                {
                    $this->imageTechnologyGalleryUpload($request, 'photo_' . $j, $j, $technology->id);
                }
            }

        }


        Session::flash('success', 'Технология успешно изменена!');
        return redirect()->back();
    }
    public function technology_destroy($id){
        $technology = Technology::find($id);
        if (!isset($technology))
        {
            return abort('404');
        }
        $technology->delete();
        Session::flash('success', 'Технология успешно удалена!');
        return redirect()->route('admin_technologies');
    }









    public function technologies_categories(){
        $this->setTitle('Список категорий');
        $categories = TechnologiesCategory::paginate(10);
        return view('admin/pages/technologies/categories-index')->with(['categories' => $categories]);
    }
    public function technologies_categories_new(){
        $this->setTitle('Создание категории');
        $categories = TechnologiesCategory::all();
        return view('admin/pages/technologies/categories-new')->with(['categories' => $categories]);
    }
    public function technologies_categories_store(StoreTechnologyCategoryRequest $request){
        $category = TechnologiesCategory::create($request->get('item'));
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('thс-' . $category->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['technologies_category_id'] = $category->id;
                    $item['locale'] = $key;
                    TechnologiesCategoriesTranslation::create($item);
                }
            }
        }
        Session::flash('success', 'Категория успешно создана!');
        return redirect()->back();
    }
    public function technologies_categories_edit($id){
        $category = TechnologiesCategory::find($id);
        if (!isset($category)) {
            return abort('404');
        }

        $this->setTitle('Изменение категории');
        $categories = TechnologiesCategory::where('id', '!=', $category->id)->get();
        return view('admin/pages/technologies/categories-new')->with(['item' => $category, 'categories' => $categories]);
    }
    public function technologies_categories_update($id, StoreTechnologyCategoryRequest $request){
        $category = TechnologiesCategory::find($id);
        if (!isset($category)) {
            return abort('404');
        }

        $category->update($request->get('item'));
        $category_locales = TechnologiesCategoriesTranslation::where('technologies_category_id', $id)->get();
        if (isset($category_locales) && count($category_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $item)
                {
                    if ($category_locales[$index]->locale == $key)
                    {
                        $category_locales[$index]->update($item);
                    }
                    $index++;
                }
            }
        }
        Session::flash('success', 'Категория успешно изменена!');
        return redirect()->back();
    }
    public function technologies_categories_destroy($id){
        $category = TechnologiesCategory::find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $category->delete();
        Session::flash('success', 'Категория успешно удалена!');
        return redirect()->route('admin_technologies_categories');
    }









    public function reviews()
    {
        $this->setTitle('Список отзывов');
        $reviews = $this->reviews->order('id', 'desc')->paginate(10);
        return view('admin/pages/reviews/index')->with(['reviews' => $reviews]);
    }

    public function reviews_new()
    {
        $this->setTitle('Создание отзыва');
        $items = Item::all();
        $users = User::all();
        return view('admin/pages/reviews/new')->with(['users' => $users, 'items' => $items]);
    }

    public function reviews_store(StoreReviewRequest $request)
    {
        $review = Review::create($request->get('item'));
        if (isset($review))
        {
            Session::flash('success', 'Отзыв успешно создан!');
            return redirect()->route('reviews');
        }
    }

    public function review_edit($id)
    {
        $review = $this->reviews->find($id);
        if (!isset($review))
        {
            return abort('404');
        }
        $this->setTitle('Редактирование отзыва');
        $items = Item::all();
        $users = User::all();
        return view('admin/pages/reviews/new')->with(['review' => $review, 'users' => $users, 'items' => $items]);
    }

    public function review_update($id, StoreReviewRequest $request)
    {
        $review = $this->reviews->find($id);
        if (!isset($review))
        {
            return abort('404');
        }
        $review->update($request->get('item'));
        Session::flash('success', 'Отзыв успешно изменен!');
        return redirect()->back();
    }

    public function review_destroy($id)
    {
        $review = $this->reviews->find($id);
        if (!isset($review))
        {
            return abort('404');
        }
        $review->delete();
        Session::flash('success', 'Отзыв успешно удален!');
        return redirect()->route('reviews');
    }










    public function table_sizes()
    {
        $this->setTitle('Список размерных таблиц');
        $tables = TableSize::paginate(10);
        return view('admin/pages/table-sizes/index')->with(['tables' => $tables]);
    }

    public function table_sizes_new()
    {
        $this->setTitle('Создание размерной таблицы');
        return view('admin/pages/table-sizes/new');
    }

    public function table_sizes_store(StoreTableSizeRequest $request)
    {
        $table = TableSize::create($request->get('item'));
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('a-' . $table->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['table_id'] = $table->id;
                    $item['locale'] = $key;
                    TableSizeTranslation::create($item);
                }
            }
        }
        Session::flash('success', 'Таблица размеров успешно создана!');
        return redirect()->back();
    }

    public function table_sizes_edit($id)
    {
        $table = TableSize::find($id);
        if (!isset($table)) {
            return abort('404');
        }
        $this->setTitle('Просмотр таблицы размеров');
        return view('admin/pages/table-sizes/new')->with(['table' => $table]);
    }

    public function table_sizes_update($id, Request $request)
    {
        //dd($request->all());
        $table = TableSize::find($id);
        if (!isset($table)) {
            return abort('404');
        }

        $table->update($request->get('item'));
        $table_locales = TableSizeTranslation::where('table_id', $id)->get();
        if (isset($table_locales) && count($table_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                //dd($table_locales);
                $table_locales[0]->update($request->get('item_locales')['en']);
                $table_locales[1]->update($request->get('item_locales')['ru']);
                $table_locales[2]->update($request->get('item_locales')['ua']);
            }
        }
        Session::flash('success', 'Таблица размеров успешно изменена!');
        return redirect()->back();
    }

    public function table_sizes_destroy($id)
    {
        $table = TableSize::find($id);
        if (!isset($table))
        {
            return abort('404');
        }
        $table->delete();
        Session::flash('success', 'Таблица размеров успешно удалена!');
        return redirect()->route('table_sizes');
    }








    public function orders()
    {
        $this->setTitle('Список заказов');
        $orders = $this->orders->order('id', 'desc')->paginate(10);
        return view('admin/pages/orders/index')->with(['orders' => $orders]);
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
                    } elseif ($key == 'client_id') {
                        $orders->whereHas('client', function ($q) use ($filter) {
                            $q->where('name', 'like', '%' . $filter . '%');
                        });
                    } else {
                        $orders->where($key, $filter);
                    }
                }
            }
//            dd($orders);
            $paginate = $orders->paginate(10);

            foreach ($filter_request as $key => $p_f)
            {
                if ($p_f != null)
                {
                    $paginate->appends('filter[' . $key . ']', $p_f);
                }
            }

            $this->setTitle('Фильтр заказов');
            return view('admin/pages/orders/index')->with(['orders' => $paginate]);
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
        $this->setTitle('Просмотр заказа');
        return view('admin/pages/orders/show')->with(['order' => $order]);
    }

    public function edit_order($id)
    {
        $order = $this->orders->find($id);
        if (!isset($order)) {
            return abort('404');
        }
        $this->setTitle('Редактирование заказа');
        $type_pay = TypePay::with('locales')->get();
        $type_delivery = TypeDelivery::with('locales')->get();
        return view('admin/pages/orders/edit')->with(['item' => $order, 'type_pay' => $type_pay, 'type_delivery' => $type_delivery]);
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










    public function clients(Request $request)
    {
            $this->setTitle('Список клиентов');
            $clients = $this->users->all();
            return view('admin/pages/clients/index')->with(['clients' => $clients]);
    }

    public function client($id)
    {
        $client = $this->users->find($id);
        if (!isset($client)) {
            return abort(404);
        }
        $this->setTitle('Профиль клиента: ' . $client->name);
        return view('admin/pages/clients/show')->with(['client' => $client]);
    }

    public function client_edit($id)
    {
        $client = $this->users->find($id);
        if (!isset($client)) {
            return abort(404);
        }
        $this->setTitle('Редактирование клиента: ' . $client->name);
        $roles = Role::all();
        $statuses = User::$userStatuses;
        return view('admin/pages/clients/new')->with(['client' => $client, 'roles' => $roles, 'statuses' => $statuses]);
    }

    public function client_update($id, Request $request)
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

    public function client_destroy($id)
    {
        $client = $this->users->find($id);
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
//            dd($orders);
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










    public function blog_categories()
    {
        $this->setTitle('Список категорий');
        $categories = $this->blog_categories->paginate(10);
        return view('admin/pages/blog/categories')->with(['categories' => $categories]);
    }

    public function blog_categories_new()
    {
        $this->setTitle('Создание категории');
        $categories = $this->blog_categories->all();
        return view('admin/pages/blog/categories-new')->with(['categories' => $categories]);
    }

    public function blog_categories_store(StoreArticleCategoryRequest $request)
    {
        $category = $this->blog_categories->create($request->get('item'));
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('ch-' . $category->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['category_id'] = $category->id;
                    $item['locale'] = $key;
                    BlogCategoriesTranslation::create($item);
                }
            }
        }
        Session::flash('success', 'Категория успешно создана!');
        return redirect()->back();
    }

    public function blog_category_edit($id)
    {
        $category = $this->blog_categories->find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $this->setTitle('Редактирование категории');
        $categories = $this->blog_categories->all();
        return view('admin/pages/blog/categories-new')->with(['article' => $category, 'categories' => $categories]);
    }

    public function blog_category_update($id, Request $request)
    {
        $item = $this->blog_categories->find($id);
        if (!isset($item))
        {
            return abort('404');
        }
        $item->update($request->get('item'));
        $item_locales = BlogCategoriesTranslation::where('category_id', $id)->get();
        if (isset($item_locales) && count($item_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $iteml)
                {
                    if ($item_locales[$index]->locale == $key)
                    {
                        //dd($item_locales[$index], $iteml);
                        $item_locales[$index]->update($iteml);
                    }
                    $index++;
                }
            }
        }




        Session::flash('success', 'Категория успешно изменена!');
        return redirect()->back();
    }

    public function blog_category_destroy($id)
    {
        $category = $this->blog_categories->find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $category->delete();
        Session::flash('success', 'Категория успешно удалена!');
        return redirect()->route('blog_categories');
    }

    public function blog_category_subcategories($id)
    {
        $category = $this->blog_categories->find($id);
        if (!isset($category))
        {
            return abort('404');
        }
        $this->setTitle('Список подкатегорий: ' . $category->locales[0]->name);
        $categories = $this->blog_categories->findWithParams(['parent_id' => $category->id])->orderBy('sort_order', 'asc')->paginate(10);
        return view('admin/pages/blog/subcategories')->with(['categories' => $categories]);
    }










    public function blog_articles()
    {
        $this->setTitle('Список материалов');
        $articles = $this->blog_articles->paginate(10);
        return view('admin/pages/blog/articles')->with(['articles' => $articles]);
    }

    public function blog_articles_new()
    {
        $this->setTitle('Создание материала');
        $categories = $this->blog_categories->all();
        return view('admin/pages/blog/articles-new')->with(['categories' => $categories]);
    }

    private function genRandomString()
    {
        $length = 10;
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ";

        $real_string_length = strlen($characters) ;
        $string="id";

        for ($p = 0; $p < $length; $p++)
        {
            $string .= $characters[mt_rand(0, $real_string_length-1)];
        }

        return strtolower($string . '_' . time());
    }

    public function blog_articles_store(StoreArticleRequest $request)
    {
        //dd($request->all());
        $article = $this->blog_articles->create($request->get('item'));
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('b-' . $article->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['article_id'] = $article->id;
                    $item['locale'] = $key;
                    BlogArticlesTranslation::create($item);
                }
            }
        }
        $categories = $request->get('categories');
        if (isset($categories)) {
            foreach ($categories as $category)
            {
                BlogMultiCategory::create([
                    'article_id' => $article->id,
                    'category_id' => $category
                ]);
            }
        }

        $item_request_preview = $request->file('item.preview');
        if (isset($item_request_preview)) {
            $preview_id = $this->imageUpload($request, 'item.preview')->id;

            if (isset($preview_id)) {
                if (isset($item->preview_id)) {
                    $img = Image::find($article->preview_id);
                    File::delete(public_path() . $img->path);
                    $img->delete();
                }
            }

            $article->update([
                'preview_id' => $preview_id
            ]);
        }

        if (isset($article)) {

            for ($j = 1; $j <= 5; $j++)
            {
                $image_gallery = $request->file('photo_' . $j);
                if (isset($image_gallery))
                {
                    $this->imageArticleGalleryUpload($request, 'photo_' . $j, $j, $article->id);
                }
            }

        }


        Session::flash('success', 'Статья успешно создана!');
        return redirect()->back();
    }

    private function imageArticleGalleryUpload(Request $request, $name, $position, $id, $status = true) {
        $this->validate($request, [
            $name => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4192',
        ]);

        if ($request->hasFile($name)) {
            $image = $request->file($name);
            $name = $this->genRandomString().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/' . Carbon::now()->format('d-m-Y') . '/');
            $image->move($destinationPath, $name);
            //$this->save();
            if ($status) {
                $check = BlogArticleGallery::where([['article_id', $id], ['position', $position]]);
                if (isset($check) && $check != null && $check->count() > 0)
                {
                    $image_model = $check->update(['path' => '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name]);
                } else {
                    $image_model = BlogArticleGallery::create(['article_id' => $id, 'position' => $position, 'path' => '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name]);
                }
                return $image_model;
            } else {
                return '/images/' . Carbon::now()->format('d-m-Y') . '/' . $name;
            }
        }
    }

    public function blog_article_edit($id)
    {
        $article = $this->blog_articles->find($id);
        if (!isset($article))
        {
            return abort('404');
        }
        $this->setTitle('Редактирование статьи');
        $categories = $this->blog_categories->all();
        return view('admin/pages/blog/articles-new')->with(['article' => $article, 'categories' => $categories]);
    }

    public function blog_article_update(StoreArticleRequest $request, $id)
    {


        $item = $this->blog_articles->find($id);
        if (!isset($item))
        {
            return abort('404');
        }
        $item->update($request->get('item'));
        $item_locales = BlogArticlesTranslation::where('article_id', $id)->get();
        if (isset($item_locales) && count($item_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $iteml)
                {
                    if ($item_locales[$index]->locale == $key)
                    {
                        //dd($item_locales[$index], $iteml);
                        $item_locales[$index]->update($iteml);
                    }
                    $index++;
                }
            }
        }

        if ($request->get('categories'))
        {
            $categories = $request->get('categories');
            BlogMultiCategory::where('article_id', $item->id)->delete();
            foreach ($categories as $category)
            {
                if (isset($category) && $category != null)
                {
                    BlogMultiCategory::create([
                        'article_id' => $item->id,
                        'category_id' => $category
                    ]);
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

            for ($j = 1; $j <= 5; $j++)
            {
                $image_gallery = $request->file('photo_' . $j);
                if (isset($image_gallery))
                {
                    $this->imageArticleGalleryUpload($request, 'photo_' . $j, $j, $item->id);
                }
            }

        }


        Session::flash('success', 'Статья успешно изменена!');
        return redirect()->back();



    }

    public function blog_article_destroy($id)
    {
        $article = $this->blog_articles->find($id);
        if (!isset($article))
        {
            return abort('404');
        }
        $article->delete();
        Session::flash('success', 'Статья успешно удалена!');
        return redirect()->route('blog_articles');
    }










    public function static_pages()
    {
        $this->setTitle('Статические страницы');
        $pages = StaticPage::paginate(10);
        return view('admin/pages/static/pages')->with(['pages' => $pages]);
    }

    public function page_new()
    {
        $this->setTitle('Создание страницы');
        return view('admin/pages/static/new');
    }

    public function page_store(StoreStaticPageRequest $request)
    {
        $page = StaticPage::create($request->get('item'));
        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    if (!isset($item['slug']))
                    {
                        $item['slug'] = str_slug('sp-' . $page->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    }
                    $item['page_id'] = $page->id;
                    $item['locale'] = $key;
                    StaticPagesTranslation::create($item);
                }
            }
        }
        Session::flash('success', 'Страница успешно создана!');
        return redirect()->back();
    }

    public function page_edit($id)
    {
        $page = StaticPage::find($id);
        $this->setTitle('Редактирование страницы');
        return view('admin/pages/static/new')->with(['page' => $page]);
    }

    public function page_update($id, StoreStaticPageRequest $request)
    {
        $page = StaticPage::find($id);
        if (!isset($page)) {
            return abort('404');
        }

        $page->update($request->get('item'));
        $page_locales = StaticPagesTranslation::where('page_id', $id)->get();
        if (isset($page_locales) && count($page_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $item)
                {
                    if ($page_locales[$index]->locale == $key)
                    {
                        $page_locales[$index]->update($item);
                    }
                    $index++;
                }
            }
        }
        Session::flash('success', 'Страница успешно изменена!');
        return redirect()->back();
    }

    public function page_destroy($id)
    {
        $page = StaticPage::find($id);
        if (!isset($page))
        {
            return abort('404');
        }
        $page->delete();
        Session::flash('success', 'Страница успешно удалена!');
        return redirect()->route('static_pages');
    }







    public function slider()
    {
        $this->setTitle('Слайды');
        $slides = Slide::all();
        return view('admin/pages/slides/slides')->with(['slides' => $slides]);
    }

    public function slide_new()
    {
        $this->setTitle('Создание слайда');
        return view('admin/pages/slides/new');
    }

    public function slide_store(StoreSlideRequest $request)
    {
        $item_request = $request->get('item');
        $item_request_preview = $request->file('item.preview');
        if (isset($item_request_preview)) {
            $preview_id = $this->slideUpload($request, 'item.preview')->id;
            $item_request['attach_id'] = $preview_id;
        }
        $i = Slide::create($item_request);

        if ($request->get('item_locales')) {
            foreach($request->get('item_locales') as $key => $item)
            {
                if (isset($item['name']))
                {
                    //dd(str_slug('sl-' . $i->id . '-' . $key . '-' . $item['name'], '-', 'en'));
                    $item['slug'] = str_slug('sl-' . $i->id . '-' . $key . '-' . $item['name'], '-', 'en');
                    $item['slide_id'] = $i->id;
                    $item['locale'] = $key;
                    SlidesTranslations::create($item);
                }
            }
        }

        Session::flash('success', 'Слайд успешно создан!');
        return redirect()->back();
    }

    private function slideUpload(Request $request, $name) {
        $this->validate($request, [
            $name => 'required|mimes:jpeg,png,jpg,gif,svg,mp4,ogx,oga,ogv,ogg,webm,3gp|max:10000',
        ]);

        if ($request->hasFile($name)) {
            $image = $request->file($name);
            $name = $this->genRandomString().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/slides/' . Carbon::now()->format('d-m-Y') . '/');
            $image->move($destinationPath, $name);
            //$this->save();

            $image_model = Image::create(['path' => '/slides/' . Carbon::now()->format('d-m-Y') . '/' . $name]);

            return $image_model;
        }
    }

    public function edit_slide($id)
    {
        $slide = Slide::find($id);
        if (!isset($slide))
        {
            return abort('404');
        }
        $this->setTitle('Редактирование слайда');
        return view('admin/pages/slides/new')->with(['item' => $slide]);
    }

    public function update_slide($id, Request $request)
    {
        $slide = Slide::find($id);
        $slide->update($request->get('item'));

        $slide_locales = SlidesTranslations::where('slide_id', $id)->get();
        if (isset($slide_locales) && count($slide_locales) > 0)
        {
            if ($request->get('item_locales'))
            {
                $index = 0;
                foreach ($request->get('item_locales') as $key => $iteml)
                {
                    if ($slide_locales[$index]->locale == $key)
                    {
                        //dd($item_locales[$index], $iteml);
                        $slide_locales[$index]->update($iteml);
                    }
                    $index++;
                }
            }
        }

        $item_request_preview = $request->file('item.preview');
        if (isset($item_request_preview)) {
            $preview_id = $this->imageUpload($request, 'item.preview')->id;

            if (isset($preview_id)) {
                if (isset($slide->attach_id)) {
                    $img = Image::find($slide->attach_id);
                    File::delete(public_path() . $img->path);
                    $img->delete();
                }
            }

            $slide->update([
                'attach_id' => $preview_id
            ]);
        }

        Session::flash('success', 'Cлайд успешно изменен!');
        return redirect()->back();

        //dd($request->all());
    }

    public function destroy_slide($id)
    {
        $slide = Slide::find($id);
        if (!isset($slide))
        {
            return abort('404');
        }
        $slide->delete();
        Session::flash('success', 'Слайд успешно удален!');
        return redirect()->route('slider');
    }







    public function report_sales()
    {
        $this->setTitle('Отчет по продажам');
        $today = Carbon::now();

        $firstTenDays = Carbon::now()->startOfMonth();
        $firstTenDaysEnd = Carbon::now()->startOfMonth()->addDays(9);

        $secondTenDays = Carbon::now()->startOfMonth()->addDays(10);
        $secondTenDaysEnd = Carbon::now()->startOfMonth()->addDays(19);

        $lastTenDays = Carbon::now()->startOfMonth()->addDays(20);
        $lastTenDaysEnd = Carbon::now()->endOfMonth();

        $sales = [];
        $sales['sales'] = $this->orders->count();

        $sales['first_day'] = Carbon::now()->startOfMonth();
        $sales['last_day'] = Carbon::now()->endOfMonth();

        $sales['first_ten_days'] = $firstTenDays;
        $sales['first_ten_days_end'] = $firstTenDaysEnd;
        $sales['sales_first_ten_days'] = $this->orders->whereBetweenDatesCreatedAt($firstTenDays, $firstTenDaysEnd)->get();
        $sales['total_sales_first_ten_days'] = $this->orders->whereBetweenDatesCreatedAt($firstTenDays, $firstTenDaysEnd)->sum('total');

        $sales['second_ten_days'] = $secondTenDays;
        $sales['second_ten_days_end'] = $secondTenDaysEnd;
        $sales['sales_second_ten_days'] = $this->orders->whereBetweenDatesCreatedAt($secondTenDays, $secondTenDaysEnd)->get();
        $sales['total_sales_second_ten_days'] = $this->orders->whereBetweenDatesCreatedAt($secondTenDays, $secondTenDaysEnd)->sum('total');

        $sales['last_ten_days'] = $lastTenDays;
        $sales['last_ten_days_end'] = $lastTenDaysEnd;
        $sales['sales_last_ten_days'] = $this->orders->whereBetweenDatesCreatedAt($lastTenDays, $lastTenDaysEnd)->get();
        $sales['total_sales_last_ten_days'] = $this->orders->whereBetweenDatesCreatedAt($lastTenDays, $lastTenDaysEnd)->sum('total');

        $sales['sales_month'] = $this->orders->whereBetweenDatesCreatedAt($firstTenDays, $lastTenDaysEnd)->get();
        $sales['total_sales_month'] = $this->orders->whereBetweenDatesCreatedAt($firstTenDays, $lastTenDaysEnd)->sum('total');

        $this->orders->whereCreatedAt($today->format('Y-m-d'))->get();
        return view('admin/pages/report/sales')->with(['sales' => $sales, 'today' => $today]);
    }

    public function report_items_views()
    {
        $this->setTitle('Отчет по просмотренным товарам');
        $items = $this->items->order('views', 'desc')->paginate(10);
        $items->getCollection()->transform(function ($item) {
            $item->qty_sales = OrderItem::where('item_id', $item->id)->count();
            return $item;
        });
        return view('admin/pages/report/items-views')->with(['items' => $items]);
    }

    public function report_items_sales()
    {
        $this->setTitle('Отчет по купленным товарам');
        $items = $this->items->order('sales', 'desc')->paginate(10);
        return view('admin/pages/report/items-sales')->with(['items' => $items]);
    }

    public function report_clients_activity()
    {
        $this->setTitle('Отчет активности покупателей');
        $activities = $this->client_activity->paginate(10);
        return view('admin/pages/report/clients-activity')->with(['activities' => $activities]);
    }

    public function report_clients_orders()
    {
        $this->setTitle('Отчет по заказам покупателей');
        $clients = $this->users->paginate(10);
        return view('admin/pages/report/clients-orders')->with(['clients' => $clients]);
    }










    public function settings()
    {
        $this->setTitle('Настройки');
        $settings = Settings::first();
        $config = Config::first();
        $social = Social::get();
        return view('admin/pages/settings/index')->with(['settings' => $settings, 'config' => $config, 'social' => $social]);
    }

    public function settings_update(StoreSettingsRequest $request)
    {
        $settingsRequest = $request->get('settings');
        $configRequest = $request->get('config');
        $socialRequest = $request->get('social');

        if (isset($settingsRequest)) {
            $settings = new \App\Settings();
            $settings->first()->update($settingsRequest);
        }
        if (isset($configRequest)) {
            $config = new \App\Config();
            $config->first()->update($configRequest);
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
}
