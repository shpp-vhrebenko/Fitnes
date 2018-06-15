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
        <form action="@if(Route::is('edit_review')) {{ route('update_review', $review->id) }} @else {{ route('admin_reviews_store') }} @endif" method="post">
            {{ csrf_field() }}
            @if(Route::is('edit_review')) <input type="hidden" name="_method" value="put"> @endif
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="base" role="tabpanel" aria-labelledby="base-tab">
                        <div class="form-group row">
                            <label for="locale" class="col-sm-2 col-form-label">Товар</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="locale" name="item[item_id]">
                                    <option selected disabled>--</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" @if(isset($review) && $review != null && $review->item_id == $item->id) selected @endif>{{ $item->locales[0]->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sort_id" class="col-sm-2 col-form-label">Комментарий</label>
                            <div class="col-sm-10">
                                <textarea class="form-control summernote" id="review" name="item[review]" rows="3">@if(isset($review)) {{ $review->review }} @endif</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Рейтинг</label>
                            <div class="col-sm-10">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="item[rating]" id="inlineRadio{{ $i }}" value="{{ $i }}" @if(isset($review) && $review->rating == $i)checked="checked"@endif>
                                        <label class="form-check-label" for="inlineRadio{{ $i }}">{{ $i }}</label>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="locale" class="col-sm-2 col-form-label">Автор</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="locale" name="item[user_id]">
                                    <option selected disabled>--</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if(isset($review) && $review != null && $review->user_id == $user->id) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="locale" class="col-sm-2 col-form-label">Локализация отзыва</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="locale" name="item[locale]">
                                    @foreach($locales as $locale)
                                        <option value="{{ $locale }}" @if(isset($review) && $review != null && $review->locale == $locale) selected @endif>{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Активация</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[is_active]">
                                    <option value="1" @if(isset($review) && $review->is_active > 0) selected @endif>Да</option>
                                    <option value="0" @if(isset($review) && $review->is_active == 0) selected @endif>Нет</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="{{ route('reviews') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
                <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_review')) Изменить @else Создать @endif</button>
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