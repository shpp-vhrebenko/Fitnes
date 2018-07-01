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
        <form action="@if(Route::is('edit_blog_article')) {{ route('blog_article_update', $article->id) }} @else {{ route('blog_articles_store') }} @endif" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if(Route::is('edit_blog_article')) <input type="hidden" name="_method" value="put"> @endif
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
                                                <label for="description" class="col-sm-2 col-form-label">Краткое писание</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control summernote" id="short_description" name="item_locales[{{ $locale }}][short_description]" rows="3">{{ isset($article) ? $article->locales[$key]->short_description : old('item_locales['.$locale.'][short_description]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="description" class="col-sm-2 col-form-label">Полное описание</label>
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
                            <label for="sticker" class="col-sm-2 col-form-label">Категории</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="categories[]" multiple>
                                    @if(isset($categories) && count($categories) > 0)
                                        @foreach($categories as $category)
                                            @if(!isset($category->parent_id))
                                                @if($category->subcategories)
                                                    <option value="{{ $category->id }}" @if(isset($article) && $article->categories->contains($category)) selected @endif>{{ $category->locales[0]->name }}</option>
                                                    @foreach($category->subcategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}" @if(isset($article) && $article->categories->contains($subcategory)) selected @endif>{{ $category->locales[0]->name }} > {{ $subcategory->locales[0]->name }}</option>
                                                        @if($subcategory->subcategories)
                                                            @foreach($subcategory->subcategories as $sub)
                                                                <option value="{{ $sub->id }}" @if(isset($article) && $article->categories->contains($sub)) selected @endif>{{ $category->locales[0]->name }} > {{ $subcategory->locales[0]->name }} > {{ $sub->locales[0]->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <option value="{{ $category->id }}" @if(isset($article) && $article->categories->contains($category)) selected @endif>{{ $category->locales[0]->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                        <div class="form-group row">
                            <label for="preview" class="col-sm-2 col-form-label">Превью материала</label>
                            <div class="col-sm-10">
                                @if(isset($article->preview))<img class="fade_preview" src="{{ asset($article->preview->path) }}" style="width: 250px;">
                                <br class="fade_preview"/><br class="fade_preview"/><button class="btn btn-danger" id="delete_preview" data-preview-id="{{ $article->preview->id }}" data-item-id="{{ $article->id }}">Удалить превью</button><br class="fade_preview"/><br class="fade_preview"/> @endif
                                <input type="file" class="form-control" id="preview" name="item[preview]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="photo" class="col-sm-2 col-form-label">Фото материала №1</label>
                            <div class="col-sm-10">
                                @if(isset($article->gallery_photo1))<img class="fade_gallery" src="{{ asset($article->gallery_photo1->path) }}" style="width: 250px;">
                                <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $article->gallery_photo1->id }}" data-item-id="{{ $article->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                                @endif
                                <input type="file" class="form-control" id="photo" name="photo_1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="photo" class="col-sm-2 col-form-label">Фото материала №2</label>
                            <div class="col-sm-10">
                                @if(isset($article->gallery_photo2))<img class="fade_gallery" src="{{ asset($article->gallery_photo2->path) }}" style="width: 250px;">
                                <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $article->gallery_photo2->id }}" data-item-id="{{ $article->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                                @endif
                                <input type="file" class="form-control" id="photo" name="photo_2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="photo" class="col-sm-2 col-form-label">Фото материала №3</label>
                            <div class="col-sm-10">
                                @if(isset($article->gallery_photo3))<img class="fade_gallery" src="{{ asset($article->gallery_photo3->path) }}" style="width: 250px;">
                                <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $article->gallery_photo3->id }}" data-item-id="{{ $article->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                                @endif
                                <input type="file" class="form-control" id="photo" name="photo_3">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="photo" class="col-sm-2 col-form-label">Фото материала №4</label>
                            <div class="col-sm-10">
                                @if(isset($article->gallery_photo4))<img class="fade_gallery" src="{{ asset($article->gallery_photo4->path) }}" style="width: 250px;">
                                <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $article->gallery_photo4->id }}" data-item-id="{{ $article->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                                @endif
                                <input type="file" class="form-control" id="photo" name="photo_4">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="photo" class="col-sm-2 col-form-label">Фото материала №5</label>
                            <div class="col-sm-10">
                                @if(isset($article->gallery_photo5))<img class="fade_gallery" src="{{ asset($article->gallery_photo5->path) }}" style="width: 250px;">
                                <br class="fade_gallery"/><br class="fade_gallery"/><button class="btn btn-danger delete_gallery" data-gallery-id="{{ $article->gallery_photo5->id }}" data-item-id="{{ $article->id }}">Удалить фото</button><br class="fade_gallery"/><br class="fade_gallery"/>
                                @endif
                                <input type="file" class="form-control" id="photo" name="photo_5">
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
                <a href="{{ route('blog_articles') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
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

          @if(isset($article))

        $('#delete_preview').click(function (e) {
          e.preventDefault();

          $.ajax({
            url: '{{ route('preview_destroy_article', $article->id) }}',
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
            url: '{{ URL::to('/') }}/admin/article-gallery-destroy/' + $(this).attr('data-gallery-id'),
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