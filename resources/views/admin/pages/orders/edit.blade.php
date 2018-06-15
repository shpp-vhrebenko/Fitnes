@extends('admin/layout/admin')

<?php
$locales = ['ru', 'ua', 'en'];
?>

@section('content')
    <h1>{{ $title }}</h1>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card">
        <form action="{{ route('update_order', $item->id) }}" method="post">
            <input type="hidden" name="_method" value="put">
            {{ csrf_field() }}
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

                        @if(isset($item->client_id) && $item->client_id != null)
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Клиент<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" disabled name="item[client_id]" value="{{ $item->client->name }}">
                                    <small id="emailHelp" class="form-text text-muted"><a href="{{ route('show_client', $item->client->id) }}" target="_blank">Просмотр клиента</a></small>
                                </div>
                            </div>
                        @else
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Клиент<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="item[guest_name]" value="{{ $item->guest_name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Город<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="item[guest_city]" value="{{ $item->guest_city }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Город<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="name" name="item[guest_email]" value="{{ $item->guest_email }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Номер телефона<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="item[guest_telephone]" value="{{ $item->guest_telephone }}">
                                </div>
                            </div>
                        @endif

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Статус заказа<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="store_subtract" name="item[status_id]">
                                        <option value="">-- Не выбрано --</option>
                                        @foreach(\App\Order::getAllOrderStatuses() as $key => $status)
                                            <option value="{{ ++$key }}" @if($key == $item->status_id) selected @endif>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Тип оплаты<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="store_subtract" name="item[pay_id]">
                                        <option value="">-- Не выбрано --</option>
                                        @foreach($type_pay as $key => $status)
                                            <option value="{{ ++$key }}" @if($key == $item->pay_id) selected @endif>{{ $status->locales[0]->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Тип доставки<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="store_subtract" name="item[delivery_id]">
                                        <option value="">-- Не выбрано --</option>
                                        @foreach($type_delivery as $key => $status)
                                            <option value="{{ ++$key }}" @if($key == $item->delivery_id) selected @endif>{{ $status->locales[0]->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">№ доставки<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="item[mail_number]" value="{{ $item->mail_number }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Итого<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="item[total]" value="{{ $item->total }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Дополнительно<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control summernote" id="description" name="item[more]" rows="3">{{ $item->more }}</textarea>
                                </div>
                            </div>

                    </div>
            </div>
            <div class="card-footer clearfix">
                <a href="{{ route('orders') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
                <button type="submit" class="btn btn-success pull-right">Изменить</button>
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