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
                                    <p>Максимальное количество символов заголовка записи <span class="badge badge-secondary">150</span></p>
                            </div>
                            </div>                            
                            <div class="form-group row">
                                <label for="text" class="col-sm-2 col-form-label">Контент записи<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control summernote" id="text" name="item[text]" rows="3" id="text">{{ isset($item) ? $item->text : old('item[text]') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status_id" class="col-sm-2 col-form-label">Cтатус Записи<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="status_id" name="item[is_active]">
                                        @if(isset($statuses))
                                            @foreach($statuses as $key => $status)
                                                <option value="{{ $key }}" @if(isset($item) && $item->is_active == $key) selected @endif>{{ $status }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>             
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="data-tab">
                    <div class="form-group row">
                        <label for="category_id" class="col-sm-2 col-form-label">Категория<sup class="required">*</sup></label>
                        <div class="col-sm-10">
                            <select class="form-control" id="category_id" name="item[category_id]" id="category_id">
                                <option value="0">-- Не выбрано --</option>
                                @if(isset($categories) && count($categories) > 0)
                                    @foreach($categories as $category)                                        
                                        <option value="{{ $category->id }}" @if(isset($item->category_id) && ($item->category_id == $category->id)) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <hr style="width: 100%;">
                    </div> 
                    <div id="item_course" class="row d-none">                          
                        <div class="form-group col-sm-6" >
                            <label for="week" class="col-sm-12 col-form-label">Курс<sup class="required">*</sup></label>
                            <div class="col-sm-12">                            
                                <select class="form-control" id="week" name="item[course_id]">
                                    <option value="1" selected>-- Не выбрано --</option>          
                                </select>
                            </div>                 
                        </div>                         
                    </div> 
                    <div id="item_training" class="row d-none" >                   
                        <div class="form-group col-sm-4" >
                            <label for="week" class="col-sm-12 col-form-label">Неделя<sup class="required">*</sup></label>
                            <div class="col-sm-12">                            
                                <select class="form-control" id="week" name="item[week]">
                                    @for($i = 0; $i < $countWeek; $i++) 
                                        {{ $numberWeek = $i + 1 }}       
                                        <option value="{{$numberWeek}}"
                                        @if(isset($item->week) && ($item->week == $numberWeek)) 
                                            selected 
                                        @endif>
                                        {{$numberWeek}}
                                        </option>                               
                                     @endfor
                                </select>
                            </div>                 
                        </div>
                        <div class="form-group col-sm-4" >
                            <label for="day" class="col-sm-12 col-form-label">День<sup class="required">*</sup></label>
                            <div class="col-sm-12">                            
                                <select class="form-control" id="day" name="item[day]">
                                    @for($i = 0; $i < $countDay; $i++) 
                                        {{ $numberDay = $i + 1 }}       
                                        <option value="{{$numberDay}}"
                                        @if(isset($item->day) && ($item->day == $numberDay)) 
                                            selected 
                                        @endif>
                                        {{$numberDay}}
                                        </option>                               
                                     @endfor
                                </select>
                            </div>                 
                        </div> 
                        <div class="form-group row col-sm-4">
                            <label for="is_holiday" class="col-sm-12 col-form-label">Статус дня<sup class="required">*</sup></label>
                            <div class="col-sm-12">
                                <select class="form-control" id="is_holiday" name="item[is_holiday]">                                    
                                    @if(isset($statusesDays))
                                        @foreach($statusesDays as $key => $statusDay)
                                            <option value="{{ $key }}" @if(isset($item) && ($item->is_holiday == $key)) dd($item->is_holiday) @endif>{{ $statusDay }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
            <a href="{{ route('admin_items') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
            <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_item')) Изменить @else Создать @endif</button>
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

        $( "#category_id" ).change(function() {
            var id_category = $( "#category_id option:selected" ).val();
            var $itemCourse = $('#item_course');
            var $itemTraining = $('#item_training');
            if(id_category == 1) {
                // display course select
                show_box($itemCourse);                
                // display training settings
                show_box($itemTraining);  
            } else if (id_category == 0) {
                hide_box($itemCourse); 
                hide_box($itemTraining); 
            } else {
                // display course select
                show_box($itemCourse); 

                // hide training settings
                if(!$('#item_training').hasClass('d-none')) {
                    hide_box($itemTraining); 
                }
            }
        });

        function show_box($item) {
            $item.addClass('d-flex');
            $item.removeClass('d-none');
        }

        function hide_box($item) {
            $item.addClass('d-none');
            $item.removeClass('d-flex');
        }
    });
    </script>
@endsection