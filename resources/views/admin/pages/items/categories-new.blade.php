@extends('admin/layout/admin')

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
        <form action="@if(Route::is('edit_category')) {{ route('update_category', $category->id) }} @else {{ route('categories_store') }} @endif" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        @if(Route::is('edit_category'))
        <input type="hidden" name="_method" value="put">
        @endif
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
                        <div class="row">                           
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Название<sup class="required">*</sup></label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                                class="form-control"
                                                id="name"
                                                name="item[name]"
                                                value="{{ isset($category) ? $category->name : old('item.name') }}"
                                                {{ (isset($category) && ($category->id == 1)) ? 'disabled' : '' }}>
                                </div>
                                </div>
                                <div class="form-group row">
                                    <label for="slug" class="col-sm-2 col-form-label">ЧПУ<sup class="required">*</sup></label>
                                    <div class="col-sm-10">
                                        <input type="text" 
                                                class="form-control"
                                                id="slug" 
                                                name="item[slug]" 
                                                value="{{ isset($category) ? $category->slug : old('item.slug') }}"
                                                {{ (isset($category) && ($category->id == 1)) ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-sm-2 col-form-label">Описание<sup class="required">*</sup></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control summernote" id="description" name="item[description]" rows="3">{{ isset($category) ? $category->description : old('item.description') }}</textarea>
                                    </div>
                                </div>                                           
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="{{ route('categories') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
                <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_category')) Изменить @else Создать @endif</button>
            </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
      $(document).ready(function () {
        $('.cancel').on("click", function () {
          return confirm('Вы действительно хотите отменить?');
        });
      });
    </script>
@endsection