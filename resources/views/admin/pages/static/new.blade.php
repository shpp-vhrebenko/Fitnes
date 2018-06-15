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
        <form action="@if(Route::is('edit_page')) {{ route('update_page', $page->id) }} @else {{ route('page_store') }}@endif" method="post">
            @if( Route::is('edit_page') ) <input type="hidden" name="_method" value="put"> @endif
            {{ csrf_field() }}
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
                    </li>
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="media-tab" data-toggle="tab" href="#media" role="tab" aria-controls="media" aria-selected="false">Медиа</a>--}}
                    {{--</li>--}}
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
                                                    <input type="text" class="form-control" id="name" name="item_locales[{{ $locale }}][name]" value="@if(isset($page)) {{ $page->locales[$key]->name }} @endif">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="slug" class="col-sm-2 col-form-label">ЧПУ</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="slug" name="item_locales[{{ $locale }}][slug]" value="@if(isset($page)) {{ $page->locales[$key]->slug }} @endif">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="short_description" class="col-sm-2 col-form-label">Краткое описание</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control summernote" id="short_description" name="item_locales[{{ $locale }}][short_description]" rows="3">@if(isset($page)) {{ $page->locales[$key]->short_description }} @endif</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="description" class="col-sm-2 col-form-label">Описание</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control summernote" id="description" name="item_locales[{{ $locale }}][description]" rows="3">@if(isset($page)) {!! $page->locales[$key]->description !!} @endif</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_title" class="col-sm-2 col-form-label">Мета-тег Title</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="meta_title" name="item_locales[{{ $locale }}][meta_title]">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_description" class="col-sm-2 col-form-label">Мета-тег Description</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="meta_description" rows="3" name="item_locales[{{ $locale }}][meta_description]"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_keywords" class="col-sm-2 col-form-label">Мета-тег Keywords</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="meta_keywords" rows="3" name="item_locales[{{ $locale }}][meta_keywords]"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tags" class="col-sm-2 col-form-label">Теги</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="tags" rows="3" name="item_locales[{{ $locale }}][tags]"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">...</div>--}}
                    <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Выводить заголовок</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[active_title]">
                                    <option value="1" @if(isset($page) && $page->active_title > 0) selected @endif>Да</option>
                                    <option value="0" @if(isset($page) && $page->active_title == 0) selected @endif>Нет</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Активировать хлебные крошки</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[active_breadcrumbs]">
                                    <option value="1" @if(isset($page) && $page->active_breadcrumbs > 0) selected @endif>Да</option>
                                    <option value="0" @if(isset($page) && $page->active_breadcrumbs == 0) selected @endif>Нет</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Добавить в меню</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[is_menu]">
                                    <option value="0" @if(isset($page) && $page->is_menu == 0) selected @endif>Нет</option>
                                    <option value="1" @if(isset($page) && $page->is_menu > 0) selected @endif>Да</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Добавить в гамбургер меню</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[is_second_menu]">
                                    <option value="0" @if(isset($page) && $page->is_second_menu == 0) selected @endif>Нет</option>
                                    <option value="1" @if(isset($page) && $page->is_second_menu > 0) selected @endif>Да</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Добавить в футер</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[is_footer_menu]">
                                    <option value="0" @if(isset($page) && $page->is_footer_menu == 0) selected @endif>Нет</option>
                                    <option value="1" @if(isset($page) && $page->is_footer_menu > 0) selected @endif>Да</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sort_order" class="col-sm-2 col-form-label">Порядок сортировки</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sort_order" name="item[sort_order]" value="{{ isset($page->sort_order) ? $page->sort_order : 0 }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Активация</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[is_active]">
                                    <option value="1" @if(isset($page) && $page->is_active > 0) selected @endif>Да</option>
                                    <option value="0" @if(isset($page) && $page->is_active == 0) selected @endif>Нет</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="{{ route('static_pages') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
                <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_page')) Изменить @else Создать @endif</button>
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