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
        <form action="@if(Route::is('edit_items_category')) {{ route('update_items_category', $category->id) }} @else {{ route('admin_categories_store') }} @endif" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if(Route::is('edit_items_category')) <input type="hidden" name="_method" value="put"> @endif
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="relations-tab" data-toggle="tab" href="#relations" role="tab" aria-controls="relations" aria-selected="false">Связи</a>
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
                                                    <input type="text" class="form-control" id="name" name="item_locales[{{ $locale }}][name]" value="{{ isset($category) ? $category->locales[$key]->name : old('item_locales['.$locale.'][name]') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="slug" class="col-sm-2 col-form-label">ЧПУ</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="slug" name="item_locales[{{ $locale }}][slug]" value="{{ isset($category) ? $category->locales[$key]->slug : old('item_locales['.$locale.'][slug]') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="description" class="col-sm-2 col-form-label">Описание</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control summernote" id="description" name="item_locales[{{ $locale }}][description]" rows="3">{{ isset($category) ? $category->locales[$key]->description : old('item_locales['.$locale.'][description]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_title" class="col-sm-2 col-form-label">Мета-тег Title</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="meta_title" name="item_locales[{{ $locale }}][meta_title]" value="{{ isset($category) ? $category->locales[$key]->meta_title : old('item_locales['.$locale.'][meta_title]') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_description" class="col-sm-2 col-form-label">Мета-тег Description</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="meta_description" rows="3" name="item_locales[{{ $locale }}][meta_description]">{{ isset($category) ? $category->locales[$key]->meta_description : old('item_locales['.$locale.'][meta_description]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="meta_keywords" class="col-sm-2 col-form-label">Мета-тег Keywords</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="meta_keywords" rows="3" name="item_locales[{{ $locale }}][meta_keywords]">{{ isset($category) ? $category->locales[$key]->meta_keywords : old('item_locales['.$locale.'][meta_keywords]') }}</textarea>
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
                            <label for="sticker" class="col-sm-2 col-form-label">Родительская категория</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[parent_id]">
                                    <option value="">-- Не выбрано --</option>
                                    @if(isset($categories) && count($categories) > 0)
                                        @foreach($categories as $categoryz)
                                            @if(isset($category))
                                                @if($category->id != $categoryz->id)
                                                    @if(!isset($categoryz->parent_id))
                                                        @if($categoryz->subcategories)
                                                            @if($category->id != $categoryz->id)
                                                                <option value="{{ $categoryz->id }}" @if(isset($current_category) && $current_category == $categoryz->id) selected @endif @if(isset($category) && $category->parent_id == $categoryz->id) selected @endif>{{ $categoryz->locales[0]->name }}</option>
                                                                @foreach($categoryz->subcategories as $subcategory)
                                                                    @if($category->id != $subcategory->id)
                                                                        <option value="{{ $subcategory->id }}"  @if(isset($current_category) && $current_category == $subcategory->id) selected @endif @if(isset($category) && $category->parent_id == $subcategory->id) selected @endif>{{ $categoryz->locales[0]->name }} > {{ $subcategory->locales[0]->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            @if($category->id != $categoryz->id)
                                                                <option value="{{ $categoryz->id }}" @if(isset($current_category) && $current_category == $categoryz->id) selected @endif @if(isset($category) && $category->parent_id == $categoryz->id) selected @endif>{{ $categoryz->locales[0]->name }}</option>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @else
                                                @if($categoryz->subcategories)
                                                    <option value="{{ $categoryz->id }}" @if(isset($current_category) && $current_category == $categoryz->id) selected @endif>{{ $categoryz->locales[0]->name }}</option>
                                                    @foreach($categoryz->subcategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}"  @if(isset($current_category) && $current_category == $subcategory->id) selected @endif>{{ $categoryz->locales[0]->name }} > {{ $subcategory->locales[0]->name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ $categoryz->id }}" @if(isset($current_category) && $current_category == $categoryz->id) selected @endif>{{ $categoryz->locales[0]->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        {{--<div class="form-group row">--}}
                            {{--<label for="terms" class="col-sm-2 col-form-label">Фильтры</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<input type="text" class="form-control" id="terms" name="item[terms]">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                    <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                        <div class="form-group row">
                            <label for="preview" class="col-sm-2 col-form-label">Превью категории</label>
                            <div class="col-sm-10">
                                @if(isset($category->preview))<img class="fade_preview" src="{{ asset($category->preview->path) }}" style="width: 250px;">
                                <br class="fade_preview"/><br class="fade_preview"/><button class="btn btn-danger" id="delete_preview" data-preview-id="{{ $category->preview->id }}" data-item-id="{{ $category->id }}">Удалить превью</button><br class="fade_preview"/><br class="fade_preview"/> @endif
                                <input type="file" class="form-control" id="preview" name="item[preview]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="preview" class="col-sm-2 col-form-label">Постер категории</label>
                            <div class="col-sm-10">
                                @if(isset($category->poster))<img class="fade_gallery" src="{{ asset($category->poster->path) }}" style="width: 250px;">
                                <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger" id="delete_gallery" data-preview-id="{{ $category->poster->id }}" data-item-id="{{ $category->id }}">Удалить постер</button><br class="fade_gallery"/><br class="fade_gallery"/> @endif
                                <input type="file" class="form-control" id="preview" name="item[poster]">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                        <div class="form-group row">
                            <label for="is_inc_menu" class="col-sm-2 col-form-label">Показывать в меню</label>
                            <div class="col-sm-10">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="item[is_inc_menu]" id="is_inc_menu" value="1" @if(isset($category->is_inc_menu) && $category->is_inc_menu > 0) checked @endif>
                                    <label class="form-check-label" for="inlineRadio1">Да</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="item[is_inc_menu]" id="is_inc_menu" value="0" @if(isset($category->is_inc_menu) && $category->is_inc_menu < 1 || !isset($category->is_inc_menu)) checked @endif>
                                    <label class="form-check-label" for="inlineRadio2">Нет</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="is_inc_menu" class="col-sm-2 col-form-label">Добавить в доп. меню</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="item[is_additional_menu]" id="is_additional_menu" value="1" @if(isset($category->is_additional_menu) && $category->is_additional_menu > 0) checked @endif>
                                    <label class="form-check-label" for="inlineRadio1">Да</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="item[is_additional_menu]" id="is_additional_menu" value="0" @if(isset($category->is_additional_menu) && $category->is_additional_menu < 1 || !isset($category->is_additional_menu)) checked @endif>
                                    <label class="form-check-label" for="inlineRadio2">Нет</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="is_inc_menu" class="col-sm-2 col-form-label">Добавить в гамбургер меню</label>
                            <div class="col-sm-10">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="item[is_second_menu]" id="is_second_menu" value="1" @if(isset($category->is_second_menu) && $category->is_second_menu > 0) checked @endif>
                                    <label class="form-check-label" for="inlineRadio1">Да</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="item[is_second_menu]" id="is_second_menu" value="0" @if(isset($category->is_second_menu) && $category->is_second_menu < 1 || !isset($category->is_second_menu)) checked @endif>
                                    <label class="form-check-label" for="inlineRadio2">Нет</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Активация</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[is_active]">
                                    <option value="1" @if(isset($category->is_active) && $category->is_active) selected @endif>Да</option>
                                    <option value="0" @if(isset($category->is_active) && !$category->is_active) selected @endif >Нет</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sort_order" class="col-sm-2 col-form-label">Порядок сортировки</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sort_order" name="item[sort_order]" @if(isset($category->is_active)) value="{{ $category->sort_order }}" @else value="{{ $count_categories }}" @endif>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="{{ route('admin_items_categories') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
                <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_items_category')) Изменить @else Создать @endif</button>
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

          @if(isset($category))

          $('#delete_preview').click(function (e) {
            e.preventDefault();

            $.ajax({
              url: '{{ route('preview_destroy_item_cat', $category->id) }}',
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


        $('#delete_gallery').click(function (e) {
          e.preventDefault();

          var selfThis = $(this);

          $.ajax({
            url: '{{ route('poster_destroy_item_cat', $category->id) }}',
            type: 'post',
            data: {
              _method: 'delete', _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (msg) {
              $('.fade_gallery').fadeOut(500);
              $('#delete_gallery').fadeOut(500);
            }
          });

        });

          @endif

      });
    </script>
@endsection