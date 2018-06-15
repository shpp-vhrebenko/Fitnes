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
        <form method="post" action="{{ route('settings_update') }}">
            {{ csrf_field() }}
            <div class="card-header">Все настройки</div>
            <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Мета-тег Title (RU)<sup class="required">*</sup></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="settings[title]" value="{{ old('', $settings->title) }}" />
                        </div>
                    </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Title (UA)<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="settings[title_ua]" value="{{ old('', $settings->title_ua) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Title (EN)<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="settings[title_en]" value="{{ old('', $settings->title_en) }}" />
                    </div>
                </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Мета-тег Description (RU)</label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" id="exampleFormControlTextarea1" name="settings[description]" rows="3">{!! old('', $settings->description) !!}</textarea>
                        </div>
                    </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Description (UA)</label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote" id="exampleFormControlTextarea1" name="settings[description_ua]" rows="3">{!! old('', $settings->description_ua) !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Description (EN)</label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote" id="exampleFormControlTextarea1" name="settings[description_en]" rows="3">{!! old('', $settings->description_en) !!}</textarea>
                    </div>
                </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Мета-тег Keywords (RU)</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[keywords]" rows="3">{!! old('', $settings->keywords) !!}</textarea>
                        </div>
                    </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Keywords (UA)</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[keywords_ua]" rows="3">{!! old('', $settings->keywords_ua) !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Мета-тег Keywords (EN)</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[keywords_en]" rows="3">{!! old('', $settings->keywords_en) !!}</textarea>
                    </div>
                </div>
                    <div class="dropdown-divider"></div><br/>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Название магазина<sup class="required">*</sup></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="settings[title_shop]" value="{{ old('', $settings->title_shop) }}" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Владелец магазина<sup class="required">*</sup></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="settings[owner]" value="{{ old('', $settings->owner) }}" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Адрес (RU)<sup class="required">*</sup></label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[address]" rows="3">{!! old('', $settings->address) !!}</textarea>
                        </div>
                    </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Адрес (UA)<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[address_ua]" rows="3">{!! old('', $settings->address_ua) !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Адрес (EN)<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[address_en]" rows="3">{!! old('', $settings->address_en) !!}</textarea>
                    </div>
                </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Геокод</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="settings[geocode]" value="{{ old('', $settings->geocode) }}" />
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
                            <input type="text" class="form-control" name="settings[telephone]" value="{{ old('', $settings->telephone) }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Режим работы (RU)</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[open]" rows="3">{!! old('', $settings->open) !!}</textarea>
                        </div>
                    </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Режим работы (UA)</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[open_ua]" rows="3">{!! old('', $settings->open_ua) !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Режим работы (EN)</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="settings[open_en]" rows="3">{!! old('', $settings->open_en) !!}</textarea>
                    </div>
                </div>
                <div class="dropdown-divider"></div><br/>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Курс доллара<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="config[exchange_rate]" value="{{ old('', $config->exchange_rate) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Лимит товаров<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="config[item_limit]" value="{{ old('', $config->item_limit) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Количество дней для новинки<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="config[duration_new]" value="{{ old('', $config->duration_new) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Количество символов<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="config[item_desc_length]" value="{{ old('', $config->item_desc_length) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Разрешить отзывы<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="config[review_active]" id="inlineRadio1" value="1" @if($config->review_active)checked="checked"@endif>
                            <label class="form-check-label" for="inlineRadio1">Да</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="config[review_active]" id="inlineRadio2" value="0" @if(!$config->review_active)checked="checked"@endif>
                            <label class="form-check-label" for="inlineRadio2">Нет</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Разрешить отзывы гостям<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="config[review_guest]" id="inlineRadio1" value="1" @if($config->review_guest)checked="checked"@endif>
                            <label class="form-check-label" for="inlineRadio1">Да</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="config[review_guest]" id="inlineRadio2" value="0" @if(!$config->review_guest)checked="checked"@endif>
                            <label class="form-check-label" for="inlineRadio2">Нет</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Оповещать по E-Mail о новых отзывах<sup class="required">*</sup></label>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="config[review_notify]" id="inlineRadio1" value="1" @if($config->review_notify)checked="checked"@endif>
                            <label class="form-check-label" for="inlineRadio1">Да</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="config[review_notify]" id="inlineRadio2" value="0" @if(!$config->review_notify)checked="checked"@endif>
                            <label class="form-check-label" for="inlineRadio2">Нет</label>
                        </div>
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
        </form>
    </div>
@endsection