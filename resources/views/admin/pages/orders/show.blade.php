@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>

    <div class="card">
        <div class="card-header">Детали заказа</div>
        <table class="table camotek-admin-table">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Клиент</th>
                <th scope="col">Тип оплаты</th>
                <th scope="col">Тип доставки</th>
                <th scope="col">Статус</th>
                <th scope="col">Дата заказа</th>
                <th scope="col">Всего</th>
                <th scope="col">Примечание</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">{{ $order->id }}</th>
                <td>@if(isset($order->client))<a href="{{ route('show_client', $order->client->id) }}">{{ $order->client->name }}</a>@else {{ $order->guest_name }} @endif</td>
                <td>{{ $order->type_pay_ru->name }}</td>
                <td>{{ $order->type_delivery_ru->name }}</td>
                <td>{{ $order->getOrderStatus($order->status_id) }}</td>
                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                <td>{{ $order->total }} грн.</td>
                <td>{{ $order->more }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <br/>

    <div class="card">
        <div class="card-header">Детали клиента</div>
        <table class="table camotek-admin-table">
            <thead>
            <tr>
                <th scope="col">Клиент</th>
                <th scope="col">Город</th>
                <th scope="col">Почта</th>
                <th scope="col">Телефон</th>
                @if($order->delivery_id > 1)<th scope="col">№ {{ $order->type_delivery_ru->name }}</th>@endif
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>@if(isset($order->client))<a href="{{ route('show_client', $order->client->id) }}">{{ $order->client->name }}</a>@else {{ $order->guest_name }} @endif</td>
                <td>@if(isset($order->client)) @if(isset($order->client->address_delivery)){{ $order->client->address_delivery->city }}@else - @endif @else {{ $order->guest_city}} @endif</td>
                <td>@if(isset($order->client)){{ $order->client->email }}@else {{ $order->guest_email }} @endif</td>
                <td>@if(isset($order->client)){{ $order->client->telephone }}@else {{ $order->guest_telephone }} @endif</td>
                @if($order->delivery_id > 1)<td>@if(isset($order->client))@if(isset($order->mail_number)) {{ $order->mail_number }} @else - @endif @else {{ $order->client->address_delivery->delivery_id }} @endif</td> @endif
            </tr>
            </tbody>
        </table>
    </div>

    <br/>

    @if(isset($order->order_items) && count($order->order_items))
        <div class="card">
            <div class="card-header">Товары заказа</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Товар</th>
                    <th scope="col">Редактировать товар</th>
                    <th scope="col">Размер</th>
                    <th scope="col">Код товара</th>
                    <th scope="col">Цена</th>
                    <th scope="col">Просмотр</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->order_items as $item)
                    <tr>
                        <th scope="row">{{ $item->item->id }}</th>
                        <td><a href="{{ route('item', $item->item->locales[0]->slug) }}">{{ $item->item->locales[0]->name }}</a></td>
                        <td><a href="{{ route('edit_item', $item->item->id) }}">Редактировать</a></td>
                        <td>@if(isset($item->size)) {{ $item->size }} @else - @endif</td>
                        <td>{{ $item->item->code }}</td>
                        <td>{{ isset($config->exchange_rate) ? intval($item->item->price * $config->exchange_rate) : $item->item->price }} грн.</td>
                        <td><a href="{{ route('item', $item->item->locales[0]->slug) }}">Просмотр</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет заказов!
        </div>
    @endif

@endsection