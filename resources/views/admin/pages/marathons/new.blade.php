@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }} <a href="{{ route('show_marathons') }}" class="fa fa-arrow-left btn-back" data-toggle="tooltip" data-placement="top" title="Вернутся назад"></a> </h1>
    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
        @endif
      @endforeach
    </div> 
    @if($errors)
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="card">
        <form @if(Route::is('edit_marathon')) action="{{ route('update_marathon', $marathon->id) }}" @else action="{{ route('marathon_store') }}" @endif method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
            @if(Route::is('edit_marathon')) <input type="hidden" name="_method" value="put"> @endif         
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
                                <label for="title" class="col-sm-2 col-form-label">Название Марафона<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="item[name]" value="{{ isset($marathon) ? $marathon->name : old('item.name') }}">
                                    <p>Максимальное количество символов названия Марафона <span class="badge badge-secondary">150</span></p>
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label for="description_cours" class="col-sm-2 col-form-label">Содержание Марафона<sup class="required">*</sup></label>
                                <div class="col-sm-10">                                    
                                    <textarea class="form-control" name="item[description]" rows="3" id="description_cours">{{ isset($marathon) ? $marathon->description : old('item.description') }}</textarea>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="period" class="col-sm-12 col-form-label">Продолжительность Марафона(дней)<sup class="required">*</sup></label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" id="period" name="item[period]" value="{{ isset($marathon) ? $marathon->period : old('item.period') }}">                         
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="dsm" class="col-sm-12 col-form-label">Дата Начала отбора</label>
                                    <div class="col-sm-12">
                                        <input type="text" data-toggle="datepicker" class="form-control" id="qty" name="item[date_start_selection]"  value="{{ isset($marathon) ? $marathon->date_start_selection : old('item.date_start_selection') }}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="dsm" class="col-sm-12 col-form-label">Дата Окончания отбора</label>
                                    <div class="col-sm-12">
                                        <input type="text" data-toggle="datepicker" class="form-control" id="qty" name="item[date_end_selection]" value="{{ isset($marathon) ? $marathon->date_end_selection : old('item.date_end_selection') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">    
                                <div class="form-group col-sm-4">
                                    <label for="status_id" class="col-sm-12 col-form-label">Cтатус Марафона<sup class="required">*</sup></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="status_id" name="item[is_active]">
                                            @if(isset($statuses))
                                                @foreach($statuses as $key => $status)
                                                    <option value="{{ $key }}" @if(isset($marathon) && $marathon->is_active == $key) selected @endif>{{ $status }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div> 
                                <div class="form-group col-sm-4">
                                    <label for="price" class="col-sm-12 col-form-label">Цена Марафона<sup class="required">*</sup></label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" id="price" name="item[price]" value="{{ isset($marathon) ? $marathon->price : old('item.price') }}"> 
                                    </div>                                      
                                </div>
                            </div>                            
                        </div>
                    </div> 
                </div>                                
                <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="relations2-tab">
                    <div class="form-group row">
                        {!! Form::label('marathon_icon', 'Иконка курса', array('class'=>'col-sm-2 control-label') ) !!}
                        <div class="col-sm-12">                     
                            <input class="filestyle"                 
                            data-buttontext="Выбрать файл"
                            data-buttonname="btn-primary"
                            data-icon="false"
                            name="item[icon]"
                            type="file"
                            id="cours_icon">                                                 
                        </div> 
                        @if(isset($marathon->icon))                 
                        <div class="col-sm-12" style="margin: 8px 0">
                            <div style="width:100%; height: auto;">
                                <img src="/uploads/icons/{{$marathon->icon}}" class="img-fluid">
                            </div>                        
                            <p>Рекомендуемые размеры изображения <span class="badge badge-secondary">100x100</span></p>
                        </div>
                        @endif
                    </div>
                </div>               
        </div>
        <div class="card-footer clearfix">
            <a href="{{ route('show_marathons') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
            <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_marathon')) Изменить @else Создать @endif</button>
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