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
        <form @if(Route::is('edit_cours')) action="{{ route('update_cours', $cours->id) }}" @else action="{{ route('cours_store') }}" @endif method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
            @if(Route::is('edit_cours')) <input type="hidden" name="_method" value="put"> @endif         
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
                                <label for="title" class="col-sm-2 col-form-label">Название курса<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="item[name]" value="{{ isset($cours) ? $cours->name : old('item.name') }}">
                                    <p>Максимальное количество символов названия Курса <span class="badge badge-secondary">150</span></p>
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label for="description_cours" class="col-sm-2 col-form-label">Содержание Курса<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="item[description]" rows="3" id="description_cours">{{ isset($cours) ? $cours->description : old('item.description') }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="period" class="col-sm-12 col-form-label">Продолжительность курса(дней)<sup class="required">*</sup></label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" id="period" name="item[period]" value="{{ isset($cours) ? $cours->period : old('item.period') }}">                                    
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="status_id" class="col-sm-12 col-form-label">Cтатус Курса<sup class="required">*</sup></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="status_id" name="item[is_active]">
                                            @if(isset($statuses))
                                                @foreach($statuses as $key => $status)
                                                    <option value="{{ $key }}" @if(isset($cours) && $cours->is_active == $key) selected @endif>{{ $status }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div> 
                                <div class="form-group col-sm-4">
                                    <label for="price" class="col-sm-12 col-form-label">Цена курса<sup class="required">*</sup></label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" id="price" name="item[price]" value="{{ isset($cours) ? $cours->price : old('item.price') }}">                                    
                                    </div>                                      
                                </div>
                            </div>                            
                        </div>
                    </div> 
                </div>                                
                <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="relations2-tab">
                    <div class="form-group row">
                        {!! Form::label('cours_icon', 'Иконка курса', array('class'=>'col-sm-2 control-label') ) !!}
                        <div class="col-sm-12">                     
                            <input class="filestyle"                 
                            data-buttontext="Выбрать файл"
                            data-buttonname="btn-primary"
                            data-icon="false"
                            name="item[icon]"
                            type="file"
                            id="cours_icon">                                                 
                        </div> 
                        @if(isset($cours->icon))                 
                        <div class="col-sm-12" style="margin: 8px 0">
                            <div style="width:100%; height: auto;">
                                <img src="/uploads/icons/{{$cours->icon}}" class="img-fluid">
                            </div>                        
                            <p>Рекомендуемые размеры изображения <span class="badge badge-secondary">100x100</span></p>
                        </div>
                        @endif
                    </div>
                </div>               
        </div>
        <div class="card-footer clearfix">
            <a href="{{ route('show_courses') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
            <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_cours')) Изменить @else Создать @endif</button>
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
        $('#description_cours').summernote({
          toolbar: [    
            ['insert', ['ul']],
          ],
          height: 200,
          focus: true,
          placeholder: "^ Для ввода Содержания Курса нужно активировать эту кнопку!!!",
        });         
    });
    </script>
@endsection