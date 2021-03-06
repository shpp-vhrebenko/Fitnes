@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }} <a href="{{ route('clients') }}" class="fa fa-arrow-left btn-back" data-toggle="tooltip" data-placement="top" title="Вернутся назад"></a></h1>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <form 
        action="@if(Route::is('edit_client')) {{ route('update_client', $client->id) }} 
                @else {{ route('client_store') }} @endif" method="post">
            {{ csrf_field() }}
            @if(Route::is('edit_attribute_term')) <input type="hidden" name="_method" value="put"> @endif
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
                            <label for="name" class="col-sm-2 col-form-label">ФИО клиента<sup class="required">*</sup></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="item[name]" value="{{ isset($client->name) ? $client->name : old('item.name') }}" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Email<sup class="required">*</sup></label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="item[email]" value="{{ isset($client->email) ? $client->email : old('item.email') }}" maxlength="150">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Пароль</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="email" name="item[password]" pattern="[A-Za-z0-9]{6,}" title="Пароль должен содержать по крайней мере шесть символов (допускаются большые, маленькие английские буквы а также  цифры от 0 до 9">
                               <!--  <input type="password" class="form-control" id="email" name="item[password]" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Пароль должен содержать по крайней мере одно число, одну большую и одну маленькую букву и не менее 8 или более символов"> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telephone" class="col-sm-2 col-form-label">Телефон<sup class="required">*</sup></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="telephone" name="item[phone]" value="{{ isset($client->phone) ? $client->phone : old('item.phone') }}" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role_id" class="col-sm-2 col-form-label">Роль клиента<sup class="required">*</sup></label>
                            <div class="col-sm-10">
                                <select class="form-control" id="role_id" name="item[role_id]">  
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @if(isset($client->roles) && $client->hasRole($role->id)) selected @elseif(!isset($client->roles) && $role->id = 3) selected @endif>{{ $role->name }}</option>
                                    @endforeach                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status_id" class="col-sm-2 col-form-label">Cтатус клиента<sup class="required">*</sup></label>
                            <div class="col-sm-10">
                                <select class="form-control" id="status_id" name="item[status_id]">
                                    @if(isset($statuses))
                                        @foreach($statuses as $key => $status)
                                            <option value="{{ $key }}" @if(isset($client) && $client->status_id == $key) selected @elseif(!isset($client) && $key == 1) selected @endif>{{ $status }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>                                                
                        <div class="form-group row" >
                            <label for="course_select" class="col-sm-12 col-form-label">Курс<sup class="required">*</sup></label>
                            <div class="col-sm-12">                            
                                <select class="form-control" id="course_select" name="item[course_id]"> 
                                    <option value="0" data-period="1">-- Не выбрано --</option>              
                                    @if(isset($courses) && count($courses) > 0)
                                        @foreach($courses as $course)                                        
                                            <option data-period="{{ $course->period }}" value="{{ $course->id }}" @if(isset($client->course_id) && ($client->course_id == $course->id)) selected @endif data-type-course="{{$course->type}}">{!! $course->name !!}</option>
                                        @endforeach
                                    @endif        
                                </select>
                            </div>                 
                        </div>                                                
                        <div class="form-group row" id="course_day">
                            <label for="dsс" class="col-sm-12 col-form-label">Текущий день курса<sup class="required">*</sup></label>
                            <div class="col-sm-12">
                                <input type="number" min="0" max="" class="form-control" id="dsc" name="current_day_course"  value="{{ isset($current_day_course)  ? $current_day_course : old('current_day_course') }}">
                            </div>
                        </div>                                          
                    </div>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="{{ route('clients') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
                <button type="submit" class="btn btn-success pull-right">@if(Route::is('edit_client')) Изменить @else Создать @endif</button>
            </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
      $(document).ready(function () {        
        var currentTypeCourse = $('#course_select').find(':selected').data('typeCourse');
        var currentCoursePeriod = $('#course_select').find(':selected').data('period');
        if(currentTypeCourse == 'cours') {
            $('#course_day').show();
            $('#dsc').attr('max', currentCoursePeriod);
        } else {
           $('#course_day').hide(); 
        }        
        $('#course_select').change(function(){
            currentTypeCourse = $(this).find(':selected').data('typeCourse');
            currentCoursePeriod = $(this).find(':selected').data('period');
            if(currentTypeCourse == 'cours') {
                $('#course_day').show();
                $('#dsc').attr('max', currentCoursePeriod);
            } else {
                $('#course_day').hide(); 
            }
        });
        $('.cancel').on("click", function () {
          return confirm('Вы действительно хотите отменить?');
        });
      });
    </script>
@endsection