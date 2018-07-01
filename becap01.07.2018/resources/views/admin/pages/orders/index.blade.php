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
                {!! input_generate('col-4', '№ Заказа', 'text', null, 'form-control mb-3', null, 'id') !!}
                {!! input_generate('col-4', 'Статус', 'select', \App\Order::getAllOrderStatuses(), 'form-control mb-3', null, 'status_id') !!}
                {!! input_generate('col-4', 'Дата добавления', 'date', null, 'form-control mb-3', null, 'created_at') !!}
            </div>
            <div class="form-row">
                {!! input_generate('col-4', 'Покупатель', 'text', null, 'form-control mb-2', null, 'client_id') !!}
                {!! input_generate('col-4', 'Итого', 'text', null, 'form-control mb-2', null, 'total') !!}
                {!! input_generate('col-4', 'Дата обновления', 'date', null, 'form-control mb-2', null, 'updated_at') !!}
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
                        <td>{{ isset($order->client) ? $order->client->name : 'Гость'}}</td>
                        <td>{{ $order->getOrderStatus($order->status_id) }}</td>
                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                        <td>{{ $order->total }} грн.</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('show_order', $order->id) }}" class="btn btn-light">Просмотр</a></li>
                                <li><a href="{{ route('edit_order', $order->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_order', $order->id) }}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input class="btn btn-danger" type="submit" value="Удалить">
                                    </form>
                                </li>
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
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить заказ?');
        });
      });
    </script>
@endsection