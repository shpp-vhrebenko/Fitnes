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
        {!! Form::open(array( 'class' => 'form-horizontal', 'role' => 'form',  'files' => true, 'action' => 'AdminController@settings_update'  ) ) !!}            
            <div class="card-header">Все настройки</div>
            <div class="card-body">
                <div class="form-group row" >
                    {!! Form::label('logo', 'Логотип', array('class'=>'col-sm-2 control-label') ) !!}
                    <div class="col-sm-6">
                        {!! Form::file('logo', array('class' => 'filestyle', 'data-value'=> $settings->logo, 'data-buttonText' => 'Выбрать файл', 'data-buttonName' => 'btn-primary', 'data-icon' => 'false', 'name' => 'logo' ) ) !!}                        
                    </div>                  
                    <div class="col-sm-4" style="margin: 8px 0">
                        <div class="bg-image" style="background-color: grey; padding: 1rem;">
                            <img src="/uploads/logo/{{$settings->logo}}">
                        </div>                        
                        <p>Рекомендуемые размеры логотипа <span class="badge badge-secondary">186 x 30</span></p>
                    </div>          
                </div>
                <div class="form-group row" >
                    {!! Form::label('favicon', 'Favicon', array('class'=>'col-sm-2 control-label') ) !!}
                    <div class="col-sm-6">
                        {!! Form::file('favicon', array('class' => 'filestyle', 'data-value'=> $settings->favicon, 'data-buttonText' => 'Выбрать файл', 'data-buttonName' => 'btn-primary', 'data-icon' => 'false', 'name' => 'favicon' ) ) !!}                        
                    </div>                  
                    <div class="col-sm-4" style="margin: 8px 0;">
                        <img src="/uploads/favicon/{{$settings->favicon}}" style="transform: scale(2); margin-left: 16px;">
                        <p>Рекомендуемые размеры favicon <span class="badge badge-secondary">16 x 16</span></p>
                    </div>          
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Title <sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="settings[title]" value="{{ old('', $settings->title) }}" />
                    </div>
                </div>               
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Description </label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote" id="exampleFormControlTextarea1" name="settings[description]" rows="3">{!! old('', $settings->description) !!}</textarea>
                    </div>
                </div>                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Keywords </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[keywords]" rows="3">{!! old('', $settings->keywords) !!}</textarea>
                    </div>
                </div>               
                <div class="dropdown-divider"></div><br/>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Название магазина<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="settings[title_site]" value="{{ old('', $settings->title_site) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Владелец магазина<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="settings[owner]" value="{{ old('', $settings->owner) }}" />
                    </div>
                </div>                                    
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">E-Mail<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="settings[email]" value="{{ old('', $settings->email) }}"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Телефон<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="settings[phone]" value="{{ old('', $settings->phone) }}"/>
                    </div>
                </div>        
                <div class="dropdown-divider"></div><br/>
                @foreach($social as $key => $item)
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Ссылка на {{ ucfirst($item->name) }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="social[{{ $item->name }}]" @if(isset($item->link)) value="{{ $item->link }}" @endif />
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer clearfix">
                <div class="float-left"><button type="button" class="btn btn-secondary">Отмена</button></div>
                <div class="float-right"><button type="submit" class="btn btn-success">Сохранить</button></div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection