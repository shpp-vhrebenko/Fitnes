@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>

    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="jumbotron jumbotron-fluid">
        <form class="form" action="{{ route('orders_filter') }}" method="get">
            <div class="form-row">
                <div class="col-3">
                    <label for="number_order">№ Заказа</label>
                    <input type="text" class="form-control mb-3" placeholder="" name="filter[id]" id="number_order">
                </div>
                <div class="col-3">
                    <label for="orderStatus">Статус Заказа</label>
                    <select class="form-control" id="orderStatus" name="filter[status_id]">                      
                        @if(isset($ordersStatuses) && count($ordersStatuses) > 0)
                            @foreach($ordersStatuses as $key => $value)                                                              
                                <option value="{{ $key }}">{{ $value }}</option>                          
                            @endforeach
                        @endif
                    </select>                    
                </div>
                <div class="col-3">
                    <label for="user_name">Покупатель</label>
                    <input type="text" class="form-control mb-3" placeholder="" name="filter[user_name]" id="user_name">
                </div>  
                <div class="col-3">
                    <label for="date">Дата добавления</label>
                    <input type="date" class="form-control mb-3" placeholder="" name="filter[created_at]" id="date">
                </div>                         
            </div>            
            <button type="submit" class="btn btn-primary mt-3 mb-2">Применить фильтр</button>
            <a href="{{ route('orders') }}" class="btn btn-danger mt-3 mb-2 ml-3">Отменить фильтр</a>
        </form>
    </div>

    @if(isset($orders) && $orders->count() > 0)
        <div class="card">
            <div class="card-header">Заказы</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Клиент</th>
                    <th scope="col">Курс</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Дата заказа</th>
                    <th scope="col">Всего</th>
                    <th scope="col">Просмотр</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <th scope="row">{{ $order->id }}</th>
                        <td>@if(isset($order->client)) 
                            {{$order->client->email}}
                            @endif
                        </td>
                        <td>@if(isset($order->course)) {!! $order->course->name !!}@endif</td>
                        <td>{{ $order->getOrderStatus($order->status_id) }}</td>
                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                        <td>{{ $order->total }} рубл.</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('show_order', $order->id) }}" class="btn btn-light">Просмотр</a></li>
                                <!-- <li><a href="{{ route('edit_order', $order->id) }}" class="btn btn-primary">Изменить</a></li> -->
                                <!-- <li>
                                    <form class="delete" action="{{ route('destroy_order', $order->id) }}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input class="btn btn-danger" type="submit" value="Удалить">
                                    </form>
                                </li> -->
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет заказов!
        </div>
    @endif
@endsection

@section('footer-scripts')
    @parent
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить заказ?');
        });
      });
    </script>
@endsection