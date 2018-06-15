@extends('admin/layout/admin')

<?php
    $locales = ['ru', 'ua', 'en'];
?>

@section('content')
    <h1>{{ $title }}</h1>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if($errors)
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="card">
        <form @if(Route::is('edit_item')) action="{{ route('update_item', $item->id) }}" @else action="{{ route('admin_items_store') }}" @endif method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
            @if(Route::is('edit_item')) <input type="hidden" name="_method" value="put"> @endif
            @if(Route::is('edit_item')) <input type="hidden" name="item_id" value="{{ $item->id }}"> @endif
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="false">Данные</a>
                </li>
                {{--<li class="nav-item">--}}
                    {{--<a class="nav-link" id="stickers-tab" data-toggle="tab" href="#stickers" role="tab" aria-controls="stickers" aria-selected="false">Стикеры</a>--}}
                {{--</li>--}}
                <li class="nav-item">
                    <a class="nav-link" id="relations-tab" data-toggle="tab" href="#relations" role="tab" aria-controls="relations" aria-selected="false">Категории</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="relations2-tab" data-toggle="tab" href="#relations2" role="tab" aria-controls="relations2" aria-selected="false">Фильтры</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="relations3-tab" data-toggle="tab" href="#relations3" role="tab" aria-controls="relations3" aria-selected="false">Технологии</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="relations5-tab" data-toggle="tab" href="#relations5" role="tab" aria-controls="relations5" aria-selected="false">Рекомендуемые</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="relations4-tab" data-toggle="tab" href="#relations4" role="tab" aria-controls="relations4" aria-selected="false">Размеры</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="characteristics-tab" data-toggle="tab" href="#characteristics" role="tab" aria-controls="characteristics" aria-selected="false">Характеристики</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="media-tab" data-toggle="tab" href="#media" role="tab" aria-controls="media" aria-selected="false">Медиа</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="other-tab" data-toggle="tab" href="#other" role="tab" aria-controls="other" aria-selected="false">Остальное</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="base" role="tabpanel" aria-labelledby="base-tab">
                    <div class="row">
                        <div class="col-1">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach($locales as $key => $locale)
                                    <a class="nav-link @if($key == 0) active @endif text-center" id="v-pills-locale-{{ $locale }}-tab" data-toggle="pill" href="#v-pills-locale-{{ $locale }}" role="tab" aria-controls="v-pills-locale-{{ $locale }}" aria-selected="true">{{ $locale }}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-11">
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach($locales as $key => $locale)
                                <div class="tab-pane fade @if($key == 0) show active @endif" id="v-pills-locale-{{ $locale }}" role="tabpanel" aria-labelledby="v-pills-locale-{{ $locale }}-tab">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Название<sup class="required">*</sup></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="name" name="item_locales[{{ $locale }}][name]" @if(isset($item)) value="{{ $item->locales[$key]->name }}" @endif>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="slug" class="col-sm-2 col-form-label">ЧПУ</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="slug" name="item_locales[{{ $locale }}][slug]" @if(isset($item)) value="{{ $item->locales[$key]->slug }}" @endif>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 col-form-label">Описание</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control summernote" id="description" name="item_locales[{{ $locale }}][description]" rows="3"> @if(isset($item)) {{ $item->locales[$key]->description }} @endif</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="meta_title" class="col-sm-2 col-form-label">Мета-тег Title</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="meta_title" name="item_locales[{{ $locale }}][meta_title]" @if(isset($item)) value="{{ $item->locales[$key]->meta_title }}" @endif>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="meta_description" class="col-sm-2 col-form-label">Мета-тег Description</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="meta_description" rows="3" name="item_locales[{{ $locale }}][meta_description]">@if(isset($item)) {{ $item->locales[$key]->meta_description }} @endif</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="meta_keywords" class="col-sm-2 col-form-label">Мета-тег Keywords</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="meta_keywords" rows="3" name="item_locales[{{ $locale }}][meta_keywords]">@if(isset($item)) {{ $item->locales[$key]->meta_keywords }} @endif</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
                    <div class="form-group row">
                        <label for="code" class="col-sm-2 col-form-label">Код товара<sup class="required">*</sup></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="code" name="item[code]" @if(isset($item)) value="{{ $item->code }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" class="col-sm-2 col-form-label">Цена</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="price" name="item[price]" @if(isset($item)) value="{{ $item->price }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="whs_price" class="col-sm-2 col-form-label">Оптовая цена</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="whs_price" name="item[whs_price]" @if(isset($item)) value="{{ $item->whs_price }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="old_price" class="col-sm-2 col-form-label">Старая цена</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="old_price" name="item[old_price]" @if(isset($item)) value="{{ $item->old_price }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-2 col-form-label">Количество</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="qty" name="item[qty]" @if(isset($item)) value="{{ $item->qty }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="min_qty" class="col-sm-2 col-form-label">Минимальное количество</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="min_qty" name="item[min_qty]" @if(isset($item)) value="{{ $item->min_qty }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="max_qty" class="col-sm-2 col-form-label">Максимальное количество</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="max_qty" name="item[max_qty]" @if(isset($item)) value="{{ $item->max_qty }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="terms" class="col-sm-2 col-form-label">Включить акцию</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="store_subtract" name="item[is_sale]">
                                <option value="0">Нет</option>
                                <option value="1" @if(isset($item) && $item->is_sale) selected @endif>Да</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-2 col-form-label">Период акции</label>
                        <div class="col-sm-10">
                            <input type="text" data-toggle="datepicker" class="form-control" id="qty" name="item[duration_sale]" @if(isset($item)) value="{{ $item->duration_sale }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="duration_new" class="col-sm-2 col-form-label">Период новинки</label>
                        <div class="col-sm-10">
                            <input type="text" data-toggle="datepicker" class="form-control" id="duration_new" name="item[duration_new]" @if(isset($item)) value="{{ $item->duration_new }}" @endif>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="store_subtract" class="col-sm-2 col-form-label">Вычитать со склада</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="store_subtract" name="item[store_subtract]">
                                <option value="0">Нет</option>
                                <option value="1" @if(isset($item) && $item->store_subtract) selected @endif>Да</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status_store_id" class="col-sm-2 col-form-label">Состояние склада</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="status_store_id" name="item[status_store_id]">
                                <option value="1" selected>В наличии</option>
                                <option value="3">Нет в наличии</option>
                                <option value="2">Ожидается</option>
                                <option value="4">Ожидание 2-3 дня</option>
                                <option value="5">Под заказ</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{--<div class="tab-pane fade" id="stickers" role="tabpanel" aria-labelledby="stickers-tab">--}}
                    {{--<div class="form-group row">--}}
                        {{--<label for="sticker" class="col-sm-2 col-form-label">Выберите стикер</label>--}}
                        {{--<div class="col-sm-10">--}}
                            {{--<input type="text" class="form-control" id="sticker" name="item[sticker]">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="tab-pane fade" id="relations" role="tabpanel" aria-labelledby="relations-tab">
                    <div class="form-group row">
                        <label for="sticker" class="col-sm-2 col-form-label">Категория товара</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="store_subtract" name="categories[]" multiple>
                                <option value="">-- Не выбрано --</option>
                                @if(isset($categories) && count($categories) > 0)
                                    @foreach($categories as $category)
                                        @if(!isset($category->parent_id))
                                            @if($category->subcategories)
                                                <option value="{{ $category->id }}" @if(isset($item->categories) && $item->categories->contains('id', $category->id)) selected @endif>{{ $category->locales[0]->name }}</option>
                                                @foreach($category->subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}" @if(isset($item->categories) && $item->categories->contains('id', $subcategory->id)) selected @endif>{{ $category->locales[0]->name }} > {{ $subcategory->locales[0]->name }}</option>
                                                    @if($subcategory->subcategories)
                                                        @foreach($subcategory->subcategories as $sub)
                                                            <option value="{{ $sub->id }}" @if(isset($item->categories) && $item->categories->contains('id', $sub->id)) selected @endif>{{ $category->locales[0]->name }} > {{ $subcategory->locales[0]->name }} > {{ $sub->locales[0]->name }}</option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @else
                                                <option value="{{ $category->id }}" @if(isset($item->categories) && $item->categories->contains('id', $category->id)) selected @endif>{{ $category->locales[0]->name }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    </div>
                <div class="tab-pane fade" id="relations2" role="tabpanel" aria-labelledby="relations2-tab">
                    <div class="form-group row">
                        <label for="terms" class="col-sm-2 col-form-label">Фильтры</label>
                        <div class="col-sm-10">
                            @if(isset($attributes))
                                <select class="form-control" id="store_subtract" name="terms[]" multiple>
                                    <option value="">-- Не выбрано --</option>
                                @foreach($attributes as $attribute)
                                    @foreach($attribute->terms_list as $term)
                                            <option value="{{ $term->id }}" @if(isset($item) && $item->terms->contains($term)) selected @endif>{{ $attribute->locales[0]->name }} > {{ $term->locales[0]->name }}</option>
                                        @endforeach
                                @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    </div>
                <div class="tab-pane fade" id="relations3" role="tabpanel" aria-labelledby="relations3-tab">

                    <div class="form-group row">
                        <label for="terms" class="col-sm-2 col-form-label">Технологии</label>
                        <div class="col-sm-10">

                            {{--@if(isset($item->technologies) && count($item->technologies) && in_array($technology->id, $item->technologies)) selected @endif--}}

                            @if(isset($technologies))
                                <select class="form-control" id="table_size" name="technologies[]" multiple>
                                    <option value="">-- Не выбрано --</option>
                                    @foreach($technologies as $technology)
                                        <option value="{{ $technology->id }}" @if(isset($technologies_exist) && count($technologies_exist) && in_array($technology->id, $technologies_exist)) selected @endif>{{ $technology->locales[0]->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    </div>
                <div class="tab-pane fade" id="relations4" role="tabpanel" aria-labelledby="relations4-tab">

                    <div class="form-group row">
                        <label for="terms" class="col-sm-2 col-form-label">Таблица размеров</label>
                        <div class="col-sm-10">
                            @if(isset($table_size))
                                <select class="form-control" id="table_size" name="table_size">
                                    <option value="">-- Не выбрано --</option>
                                    @foreach($table_size as $table)
                                        <option value="{{ $table->id }}" @if(isset($item->table_size) && count($item->table_size) && $item->table_size[0]->id == $table->id) selected @endif>{{ $table->locales[0]->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="relations5" role="tabpanel" aria-labelledby="relations5-tab">

                    <div class="form-group row">
                        <label for="terms" class="col-sm-2 col-form-label">Товары</label>
                        <div class="col-sm-10">

                            {{--@if(isset($item->technologies) && count($item->technologies) && in_array($technology->id, $item->technologies)) selected @endif--}}

                            @if(isset($items_related))
                                <select class="form-control" id="table_size" name="items_related[]" multiple>
                                    <option value="">-- Не выбрано --</option>
                                    @foreach($items_related as $item_rel)
                                        <option value="{{ $item_rel->id }}" @if(isset($item) && isset($item->recommended_items) && $item->recommended_items != null && $item->recommended_items->contains($item_rel->id)) selected @endif>{{ $item_rel->locales[0]->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="characteristics" role="tabpanel" aria-labelledby="characteristics-tab">
                    @if(isset($characteristics))
                        @foreach($characteristics as $characteristic)

                            @if(isset($characteristic->locales))
                                @foreach($characteristic->locales as $locale)

                                    <?php $l = []; ?>
                                    @if(isset($item->characteristics_without_locale) && count($item->characteristics_without_locale))
                                        @foreach($item->characteristics_without_locale as $item_ch)
                                            @if($locale->ch_id == $item_ch->ch_id && $locale->locale == $item_ch->locale)
                                                <?php array_push($l, $locale->locale); ?>
                                                <div class="form-group row">
                                                    <label for="preview" class="col-sm-2 col-form-label">{{ $locale->name }}</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="item_ch[{{ $item_ch->id }}]" value="{{ unserialize($item_ch->value) }}">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                        @if(!in_array($locale->locale, $l))
                                            <div class="form-group row">
                                                <label for="preview" class="col-sm-2 col-form-label">{{ $locale->name }}</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="characteristics[{{ $locale->ch_id }}][{{$locale->locale}}]">
                                                </div>
                                            </div>
                                        @endif

                                @endforeach
                                    <hr/>
                            @endif

                        @endforeach
                    @endif
                </div>
                <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                    <div class="form-group row">
                        <label for="preview" class="col-sm-2 col-form-label">Превью товара</label>
                        <div class="col-sm-10">
                            @if(isset($item->preview))<img class="fade_preview" src="{{ asset($item->preview->path) }}" style="width: 250px;">
                            <br class="fade_preview"/><br class="fade_preview"/><button class="btn btn-danger" id="delete_preview" data-preview-id="{{ $item->preview->id }}" data-item-id="{{ $item->id }}">Удалить превью</button><br class="fade_preview"/><br class="fade_preview"/> @endif
                            <input type="file" class="form-control" id="preview" name="item[preview]">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №1</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo1))<img class="fade_gallery" src="{{ asset($item->gallery_photo1->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo1->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №2</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo2))<img class="fade_gallery" src="{{ asset($item->gallery_photo2->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo2->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №3</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo3))<img class="fade_gallery" src="{{ asset($item->gallery_photo3->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo3->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_3">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №4</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo4))<img class="fade_gallery" src="{{ asset($item->gallery_photo4->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo4->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_4">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №5</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo5))<img class="fade_gallery" src="{{ asset($item->gallery_photo5->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo5->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_5">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №6</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo6))<img class="fade_gallery" src="{{ asset($item->gallery_photo6->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo6->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_6">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №7</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo7))<img class="fade_gallery" src="{{ asset($item->gallery_photo7->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo7->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_7">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №8</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo8))<img class="fade_gallery" src="{{ asset($item->gallery_photo8->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo8->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_8">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №9</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo9))<img class="fade_gallery" src="{{ asset($item->gallery_photo9->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo9->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_9">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №10</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo10))<img class="fade_gallery" src="{{ asset($item->gallery_photo10->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo10->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_10">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №11</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo11))<img class="fade_gallery" src="{{ asset($item->gallery_photo11->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo11->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_11">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №12</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo12))<img class="fade_gallery" src="{{ asset($item->gallery_photo12->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo12->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_12">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №13</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo13))<img class="fade_gallery" src="{{ asset($item->gallery_photo13->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo13->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_13">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №14</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo14))<img class="fade_gallery" src="{{ asset($item->gallery_photo14->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo14->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_14">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Фото товара №15</label>
                        <div class="col-sm-10">
                            @if(isset($item->gallery_photo15))<img class="fade_gallery" src="{{ asset($item->gallery_photo15->path) }}" style="width: 250px;">
                            <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $item->gallery_photo15->id }}" data-item-id="{{ $item->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo_15">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="meta_keywords" class="col-sm-2 col-form-label">Youtube видео</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="meta_keywords" rows="3" name="item[youtube]">@if(isset($item)) {{ $item->youtube }} @endif</textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                    <div class="form-group row">
                        <label for="sticker" class="col-sm-2 col-form-label">Активация</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="store_subtract" name="item[is_active]">
                                <option value="1" @if(isset($item->is_active) && $item->is_active) selected @endif>Да</option>
                                <option value="0" @if(isset($item->is_active) && !$item->is_active) selected @endif >Нет</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sticker" class="col-sm-2 col-form-label">Показывать в каталоге</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="store_subtract" name="item[is_catalog]">
                                <option value="1" @if(isset($item->is_catalog) && $item->is_catalog) selected @endif>Да</option>
                                <option value="0" @if(isset($item->is_catalog) && !$item->is_catalog) selected @endif >Нет</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer clearfix">
            <a href="{{ route('admin_items') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
            <button type="submit" class="btn btn-success pull-right">Создать</button>
        </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.cancel').on("click", function () {
          return confirm('Вы действительно хотите отменить?');
        });

          @if(isset($item))

        $('#delete_preview').click(function (e) {
          e.preventDefault();

          $.ajax({
            url: '{{ route('preview_destroy', $item->id) }}',
            type: 'post',
            data: {
              _method: 'delete', _token: $('meta[name="csrf-token"]').attr('content'),
            },
              success: function (msg) {
                $('.fade_preview').fadeOut(500);
                $('#delete_preview').fadeOut(500);
            }
          });

        });


        $('.delete_gallery').click(function (e) {
          e.preventDefault();

          var selfThis = $(this);

          $.ajax({
            url: '{{ URL::to('/') }}/admin/gallery-destroy/' + $(this).attr('data-gallery-id'),
            type: 'post',
            data: {
              _method: 'delete', _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (msg) {
              selfThis.parent().find('.fade_gallery').fadeOut(500);
              selfThis.fadeOut(500);
            }
          });

        });

          @endif



      });
    </script>
@endsection