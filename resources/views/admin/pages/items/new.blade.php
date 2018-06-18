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
        <form @if(Route::is('edit_item')) action="{{ route('update_item', $item->id) }}" @else action="{{ route('items_store') }}" @endif method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
            @if(Route::is('edit_item')) <input type="hidden" name="_method" value="put"> @endif
            @if(Route::is('edit_item')) <input type="hidden" name="item_id" value="{{ $item->id }}"> @endif
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
                </li>        
               
                <li class="nav-item">
                    <a class="nav-link" id="relations-tab" data-toggle="tab" href="#categories" role="tab" aria-controls="relations" aria-selected="false">Категории</a>
                </li>                
                <li class="nav-item">
                    <a class="nav-link" id="characteristics-tab" data-toggle="tab" href="#courses" role="tab" aria-controls="characteristics" aria-selected="false">Курсы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="media-tab" data-toggle="tab" href="#media" role="tab" aria-controls="media" aria-selected="false">Медиа</a>
                </li>               
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="base" role="tabpanel" aria-labelledby="base-tab">
                    <div class="row">                      
                        <div class="col-12">
                             <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">Заголовок записи<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="item[title]" value="{{ isset($item) ? $item->title : old('item[title]') }}">
                            </div>
                            </div>                            
                            <div class="form-group row">
                                <label for="text" class="col-sm-2 col-form-label">Контент записи</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control summernote" id="description" name="item[text]" rows="3" id="text">{{ isset($item) ? $item->text : old('item[text]') }}</textarea>
                                </div>
                            </div>             
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="data-tab">
                    <div class="form-group row">
                        <label for="category_id" class="col-sm-2 col-form-label">Категория</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="store_subtract" name="item[category_id]" id="category_id">
                                <option value="">-- Не выбрано --</option>
                                @if(isset($categories) && count($categories) > 0)
                                    @foreach($categories as $category)                                        
                                        <option value="{{ $category->id }}" @if(isset($item->category_id) && ($item->category_id == $category->id)) selected @endif>{{ $category->name }}</option>                      
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>                   
                </div>             
                <div class="tab-pane fade" id="courses" role="tabpanel" aria-labelledby="relations-tab">
                
                </div>
                <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="relations2-tab">
                    <div class="form-group row">
                        {!! Form::label('image', 'Изображение записи', array('class'=>'col-sm-2 control-label') ) !!}
                        <div class="col-sm-4">                            
                            @if(isset($item->image)) 
                                <input class="filestyle" 
                                data-value="/uploads/image_items/{{$item->image}}"    
                                data-buttontext="Выбрать файл"
                                data-buttonname="btn-primary"
                                data-icon="false"
                                name="item[image]"
                                type="file"
                                id="image">
                            @else
                                <input class="filestyle"                           
                                data-buttontext="Выбрать файл"
                                data-buttonname="btn-primary"
                                data-icon="false"
                                name="item[image]"
                                type="file"
                                id="image">                                         
                            @endif                      
                        </div> 
                        @if(isset($item->image))                 
                        <div class="col-sm-6" style="margin: 8px 0">
                            <div style="width:100%; height: auto;">
                                <img src="/uploads/image_items/{{$item->image}}" class="img-fluid">
                            </div>                        
                            <p>Рекомендуемые размеры изображения <span class="badge badge-secondary"></span></p>
                        </div>
                        @endif
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
    @parent
    <script>
      $(document).ready(function () {
        $('.cancel').on("click", function () {
          return confirm('Вы действительно хотите отменить?');
        });
      });
    </script>
@endsection