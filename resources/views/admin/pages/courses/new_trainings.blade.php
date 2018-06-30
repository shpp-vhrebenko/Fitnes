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
        <form @if(Route::is('edit_training')) action="{{ route('update_training', $item->id) }}" @else action="{{ route('training_store') }}" @endif method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
            <input type="hidden" name="item[course_id]" value="{{ $item->course_id }}">
            <input type="hidden" name="item[category_id]" value="{{ $item->category_id }}">
            @if(Route::is('edit_training')) 
            <input type="hidden" name="_method" value="put">
            @endif
            <input type="hidden" name="numberDay" value="{{ $numberDay }}">            
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
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
                                    <input type="text" class="form-control" id="name" name="item[title]" value="{{ isset($item->title) ? $item->title : old('item.title') }}">
                                    <p>Максимальное количество символов заголовка записи <span class="badge badge-secondary">150</span></p>
                            </div>
                            </div>                            
                            <div class="form-group row">
                                <label for="text" class="col-sm-2 col-form-label">Контент записи<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control summernote" id="text" name="item[text]" rows="3" id="text">{{ isset($item->text) ? $item->text : old('item.text') }}</textarea>
                                </div>
                            </div>                
                                <!-- <div class="form-group col-sm-6">
                                    <label for="status_id" class="col-sm-12 col-form-label">Cтатус Записи<sup class="required">*</sup></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="status_id" name="item[is_active]">
                                            @if(isset($statuses))
                                                @foreach($statuses as $key => $status)
                                                    <option value="{{ $key }}" @if(isset($item->is_active) && $item->is_active == $key) selected @endif>{{ $status }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label for="is_holiday" class="col-sm-2 col-form-label">Статус дня<sup class="required">*</sup></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="is_holiday" name="item[is_holiday]">
                                            @if(isset($statusesDays))
                                                @foreach($statusesDays as $key => $statusDay)
                                                    <option value="{{ $key }}" @if(
                                                    isset($item) && ($item->is_holiday == $key)) selected @endif>{{ $statusDay }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>                     
                                       
                        </div>
                    </div>
                </div>                            
                <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="relations2-tab">
                    <div class="form-group row">
                        {!! Form::label('image', 'Изображение записи', array('class'=>'col-sm-2 control-label') ) !!}
                        <div class="col-sm-12">                            
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
                        <div class="col-sm-12" style="margin: 8px 0">
                            <div style="width:100%; height: auto;">
                                <img src="/uploads/items/{{$item->image}}" class="img-fluid">
                            </div>                        
                            <p>Рекомендуемые размеры изображения <span class="badge badge-secondary">300x300</span></p>
                        </div>
                        @endif
                    </div>
                </div>               
        </div>
        <div class="card-footer clearfix">
            <a href="{{ route('course_trainings', $item->course_id) }}" class="btn btn-secondary pull-left cancel">Отменить</a>
            <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_training')) Изменить @else Создать @endif</button>
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