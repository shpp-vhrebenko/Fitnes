@extends('admin/layout/admin')


@section('content')
    <h1>{{ $title }} <a href="{{ route('orders') }}" class="fa fa-arrow-left btn-back" data-toggle="tooltip" data-placement="top" title="Вернутся назад"></a></h1>
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

                        @if(isset($item->user_id) && $item->user_id != null)
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Клиент<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" disabled name="item[user_id]" value="{{ $item->client->name }}">
                                    <small id="emailHelp" class="form-text text-muted"><a href="{{ route('show_client', $item->client->id) }}" target="_blank">Просмотр клиента</a></small>
                                </div>
                            </div>             
                        @endif
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Статус заказа<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="store_subtract" name="item[status_id]">
                                        <option value="">-- Не выбрано --</option>
                                        @foreach(\App\Order::getAllOrderStatuses() as $key => $status)
                                            <option value="{{ $key }}" @if($key == $item->status_id) selected @endif>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Итого<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" class="form-control" id="name" name="item[total]" value="{{ $item->total }}">
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