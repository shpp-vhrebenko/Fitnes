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
        <form action="@if(Route::is('edit_blog_category')) {{ route('blog_category_update', $article->id) }} @else {{ route('blog_categories_store') }} @endif" method="post">
            {{ csrf_field() }}
            @if(Route::is('edit_blog_category')) <input type="hidden" name="_method" value="put"> @endif
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
                                                    <input type="text" class="form-control" id="name" name="item_locales[{{ $locale }}][name]" value="{{ isset($article) ? $article->locales[$key]->name : old('item_locales['.$locale.'][name]') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="slug" class="col-sm-2 col-form-label">ЧПУ</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="slug" name="item_locales[{{ $locale }}][slug]" value="{{ isset($article) ? $article->locales[$key]->slug : old('item_locales['.$locale.'][slug]') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="description" class="col-sm-2 col-form-label">Описание</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control summernote" id="description" name="item_locales[{{ $locale }}][description]" rows="3">{{ isset($article) ? $article->locales[$key]->description : old('item_locales['.$locale.'][description]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_title" class="col-sm-2 col-form-label">Мета-тег Title</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="meta_title" name="item_locales[{{ $locale }}][meta_title]" value="{{ isset($article) ? $article->locales[$key]->meta_title : old('item_locales['.$locale.'][meta_title]') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_description" class="col-sm-2 col-form-label">Мета-тег Description</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="meta_description" rows="3" name="item_locales[{{ $locale }}][meta_description]">{{ isset($article) ? $article->locales[$key]->meta_description : old('item_locales['.$locale.'][meta_description]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_keywords" class="col-sm-2 col-form-label">Мета-тег Keywords</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="meta_keywords" rows="3" name="item_locales[{{ $locale }}][meta_keywords]">{{ isset($article) ? $article->locales[$key]->meta_keywords : old('item_locales['.$locale.'][meta_keywords]') }}</textarea>
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
                            <label for="sticker" class="col-sm-2 col-form-label">Категория</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[parent_id]">
                                    <option selected value>-- Не выбрано --</option>
                                    @if(isset($categories) && count($categories) > 0)
                                        @foreach($categories as $categoryz)
                                            @if(isset($article) && $article->id != $categoryz->id && $categoryz->parent_id != $article->id)
                                                <option value="{{ $categoryz->id }}" @if(isset($article) && $article->parent_id == $categoryz->id) selected @endif>{{ $categoryz->locales[0]->name }}</option>
                                            @else
                                                <option value="{{ $categoryz->id }}">{{ $categoryz->locales[0]->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
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
                <a href="{{ route('blog_categories') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
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
      });
    </script>
@endsection