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
        <form action="@if(Route::is('edit_attribute')) {{ route('update_attribute', $attribute->id) }} @else {{ route('admin_attributes_store') }} @endif" method="post">
            {{ csrf_field() }}
            @if(Route::is('edit_attribute')) <input type="hidden" name="_method" value="put"> @endif
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="relations-tab" data-toggle="tab" href="#relations" role="tab" aria-controls="relations" aria-selected="false">Связи</a>
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
                                                    <input type="text" class="form-control" id="name" name="item_locales[{{ $locale }}][name]" value="{{ isset($attribute) ? $attribute->locales[$key]->name : old('item_locales['.$locale.'][name]') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="slug" class="col-sm-2 col-form-label">ЧПУ</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="slug" name="item_locales[{{ $locale }}][slug]" value="{{ isset($attribute) ? $attribute->locales[$key]->slug : old('item_locales['.$locale.'][slug]') }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="relations" role="tabpanel" aria-labelledby="relations-tab">
                        <div class="form-group row">
                            <label for="only_categories" class="col-sm-2 col-form-label">Активировать для категорий</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="only_categories" name="item[only_categories][]" multiple>
                                    <option value="">-- Для всех --</option>
                                    @if(isset($categories) && count($categories) > 0)
                                        @foreach($categories as $category)
                                            @if(!isset($category->parent_id))
                                                @if($category->subcategories)
                                                    <option value="{{ $category->id }}" @if(isset($attribute) && isset($attribute->only_categories) && in_array($category->id, $attribute->only_categories)) selected @endif>{{ $category->locales[0]->name }}</option>
                                                    @foreach($category->subcategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}" @if(isset($attribute) && isset($attribute->only_categories) && in_array($subcategory->id, $attribute->only_categories)) selected @endif>{{ $category->locales[0]->name }} > {{ $subcategory->locales[0]->name }}</option>
                                                        @if($subcategory->subcategories)
                                                            @foreach($subcategory->subcategories as $sub)
                                                                <option value="{{ $sub->id }}" @if(isset($attribute) && isset($attribute->only_categories) && in_array($sub->id, $attribute->only_categories)) selected @endif>{{ $category->locales[0]->name }} > {{ $subcategory->locales[0]->name }} > {{ $sub->locales[0]->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <option>{{ $category->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                        <div class="form-group row">
                            <label for="is_filter" class="col-sm-2 col-form-label">Показывать в фильтре</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="item[is_filter]" id="is_filter" value="1" @if(isset($attribute) && $attribute->is_filter > 0) checked @endif>
                                    <label class="form-check-label" for="inlineRadio1">Да</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="item[is_filter]" id="is_filter" value="0" @if(isset($attribute) && $attribute->is_filter == 0) checked @endif>
                                    <label class="form-check-label" for="inlineRadio2">Нет</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sort_id" class="col-sm-2 col-form-label">Тип сортировки</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[sort_id]">
                                    <option value="0" @if(isset($attribute) && $attribute->sort_id == 0) selected @endif>По возрастанию</option>
                                    <option value="1" @if(isset($attribute) && $attribute->sort_id == 1) selected @endif>По убыванию</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Активация</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[is_active]">
                                    <option value="1" @if(isset($attribute) && $attribute->is_active > 0) selected @endif>Да</option>
                                    <option value="0" @if(isset($attribute) && $attribute->is_active == 0) selected @endif>Нет</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sort_order" class="col-sm-2 col-form-label">Порядок сортировки</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sort_order" name="item[sort_order]" value="{{ isset($attribute->sort_order) ? $attribute->sort_order : 0 }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="{{ route('admin_items_attributes') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
                <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_attribute')) Изменить @else Создать @endif</button>
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
      });
    </script>
@endsection