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
        <form action="@if(Route::is('edit_table')) {{ route('update_table_sizes', $table->id) }} @else {{ route('table_sizes_store') }} @endif" method="post">
            {{ csrf_field() }}


            @if(Route::is('edit_table')) <input type="hidden" name="_method" value="put"> @endif
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
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
                                    <div class="tab-pane fade show active" id="v-pills-locale-ru" role="tabpanel" aria-labelledby="v-pills-locale-ru-tab">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-form-label">Название<sup class="required">*</sup></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="name" name="item_locales[ru][name]" value="{{ isset($table) ? $table->locales[1]->name : old('item_locales[ru][name]') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="description" class="col-sm-2 col-form-label">Описание</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control summernote" id="description" name="item_locales[ru][description]" rows="3">{{ isset($table) ? $table->locales[1]->description : old('item_locales[ru][description]') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-locale-ua" role="tabpanel" aria-labelledby="v-pills-locale-ua-tab">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-form-label">Название<sup class="required">*</sup></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="name" name="item_locales[ua][name]" value="{{ isset($table) ? $table->locales[2]->name : old('item_locales[ua][name]') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="description" class="col-sm-2 col-form-label">Описание</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control summernote" id="description" name="item_locales[ua][description]" rows="3">{{ isset($table) ? $table->locales[2]->description : old('item_locales[ua][description]') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-locale-en" role="tabpanel" aria-labelledby="v-pills-locale-en-tab">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-form-label">Название<sup class="required">*</sup></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="name" name="item_locales[en][name]" value="{{ isset($table) ? $table->locales[0]->name : old('item_locales[en][name]') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="description" class="col-sm-2 col-form-label">Описание</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control summernote" id="description" name="item_locales[en][description]" rows="3">{{ isset($table) ? $table->locales[0]->description : old('item_locales[en][description]') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                        <div class="form-group row">
                            <label for="sticker" class="col-sm-2 col-form-label">Активация</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="store_subtract" name="item[is_active]">
                                    <option value="1" @if(isset($table->is_active) && $table->is_active) selected @endif>Да</option>
                                    <option value="0" @if(isset($table->is_active) && !$table->is_active) selected @endif >Нет</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card-footer clearfix">
                <a href="{{ route('table_sizes') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
                <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_table')) Изменить @else Создать @endif</button>
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