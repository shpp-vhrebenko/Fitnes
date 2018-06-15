@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>

    <section class="camotek-admin-index-modules">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Профиль клиента
                    </div>
                    <table class="table camotek-admin-table">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">ФИО</th>
                            <th scope="col">Почта</th>
                            <th scope="col">Телефон</th>
                            <th scope="col">IP</th>
                            <th scope="col">Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->telephone }}</td>
                            <td>{{ $client->ip }}</td>
                            <td>{{ $client->getClientStatus($client->status_id) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col">
                    @if(isset($client->orders) && count($client->orders))
                    <div class="card">
                        <div class="card-header">
                            Последние заказы
                        </div>
                        <table class="table camotek-admin-table">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Дата заказа</th>
                                <th scope="col">Всего</th>
                                <th scope="col">Просмотр</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($client->getLatestOrdersByQty(5) as $order)
                                <tr>
                                    <th scope="row">{{ $order->id }}</th>
                                    <td>{{ $order->getOrderStatus($order->status_id) }}</td>
                                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $order->total }} грн.</td>
                                    <td><a href="{{ route('show_order', $order->id) }}" class="btn btn-primary">Просмотр заказа</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-secondary" role="alert">
                            У клиента ещё нет заказов!
                        </div>
                    @endif
            </div>
        </div>

        <div class="row">
            <div class="col">
                @if(isset($client->address))
                    <div class="card">
                        <div class="card-header">
                            Адрес клиента
                        </div>
                        <table class="table camotek-admin-table">
                            <thead>
                            <tr>
                                <th scope="col">Страна</th>
                                <th scope="col">Область</th>
                                <th scope="col">Пункт</th>
                                <th scope="col">Адрес</th>
                                <th scope="col">Индекс</th>
                                <th scope="col">Дата создания</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">{{ $client->address->country }}</th>
                                <th scope="row">{{ $client->address->region }}</th>
                                <th scope="row">{{ $client->address->city }}</th>
                                <th scope="row">{{ $client->address->address }}</th>
                                <th scope="row">{{ $client->address->zipcode }}</th>
                                <th scope="row">{{ $client->address->created_at->format('d-m-Y') }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary" role="alert">
                        У клиента ещё нет адреса!
                    </div>
                @endif
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col">
                @if(isset($client->address_delivery))
                    <div class="card">
                        <div class="card-header">
                            Адрес доставки
                        </div>
                        <table class="table camotek-admin-table">
                            <thead>
                            <tr>
                                <th scope="col">Страна</th>
                                <th scope="col">Область</th>
                                <th scope="col">Пункт</th>
                                <th scope="col">Адрес</th>
                                <th scope="col">Индекс</th>
                                <th scope="col">Дата создания</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">{{ $client->address_delivery->country }}</th>
                                <th scope="row">{{ $client->address_delivery->region }}</th>
                                <th scope="row">{{ $client->address_delivery->city }}</th>
                                <th scope="row">{{ $client->address_delivery->address }}</th>
                                <th scope="row">{{ $client->address_delivery->zipcode }}</th>
                                <th scope="row">{{ $client->address_delivery->created_at->format('d-m-Y') }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary" role="alert">
                        У клиента ещё нет адреса доставки!
                    </div>
                @endif
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col">
                @if(isset($client->cart) && count($client->cart))
                    <div class="card">
                        <div class="card-header">
                            Корзина клиента
                        </div>
                        <table class="table camotek-admin-table">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Товар</th>
                                <th scope="col">Количество</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Дата создания</th>
                                <th scope="col">Дата изменения</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($client->cart as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td>{{ $item->item->locales[1]->name }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->item->price * $item->qty }} грн.</td>
                                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $item->updated_at ? $item->updated_at->format('d-m-Y') : '-' }}</td>
                                    {{--<td>{{ $order->total }} грн.</td>--}}
                                    {{--<td><a href="{{ route('show_order', $order->id) }}" class="btn btn-primary">Просмотр заказа</a></td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        У клиента пустая корзина!
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection