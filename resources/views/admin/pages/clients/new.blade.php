@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card">
        <form action="@if(Route::is('edit_client')) {{ route('update_client', $client->id) }} @else {{ route('client_store', $client->id) }} @endif" method="post">
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
                            <label for="name" class="col-sm-2 col-form-label">ФИО клиента</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="item[name]" @if(isset($client)) value="{{ $client->name }}" @endif>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="item[email]" @if(isset($client)) value="{{ $client->email }}" @endif>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telephone" class="col-sm-2 col-form-label">Телефон</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="telephone" name="item[telephone]" @if(isset($client)) value="{{ $client->telephone }}" @endif>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role_id" class="col-sm-2 col-form-label">Роль клиента</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="role_id" name="item[role_id]">
                                    @if(isset($client->roles))
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if($client->hasRole($role->id)) selected @endif>{{ $role->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status_id" class="col-sm-2 col-form-label">Cтатус клиента</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="status_id" name="item[status_id]">
                                    @if(isset($statuses))
                                        @foreach($statuses as $key => $status)
                                            <option value="{{ ++$key }}" @if(isset($client) && $client->status_id == $key) selected @endif>{{ $status }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="is_subscribe" class="col-sm-2 col-form-label">Подписка на рассылку</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="is_subscribe" name="item[is_subscribe]">
                                    <option value="">-- Не выбрано --</option>
                                    <option value="1" @if(isset($client->is_subscribe) && $client->is_subscribe == 1) selected @endif>Да</option>
                                    <option value="0" @if(isset($client->is_subscribe) && $client->is_subscribe < 1) selected @endif>Нет</option>
                                </select>
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
    <script>
      $(document).ready(function () {
        $('.cancel').on("click", function () {
          return confirm('Вы действительно хотите отменить?');
        });
      });
    </script>
@endsection