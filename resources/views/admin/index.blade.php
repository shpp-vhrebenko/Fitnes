@extends('admin/layout/admin')

@section('header-styles')     
    @parent         
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')
    <section class="camotek-admin-hero-stats">
        <div class="row">           
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        Количество клиентов
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $stats['users'] }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        Количество заказов
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $stats['orders'] }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        Заработанно
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $stats['total'] }} руб.</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>    
    <section class="camotek-admin-index-modules">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Последние заказы
                    </div>
                    @if(isset($latest_orders) && $latest_orders->count() > 0)
                        <table class="table camotek-admin-table">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Клиент</th>
                                <th scope="col">Курс</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Дата заказа</th>
                                <th scope="col">Тип оплаты</th>
                                <th scope="col">Всего</th>
                                <th scope="col">Просмотр</th>                    
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($latest_orders as $order)
                                <tr>
                                    <th scope="row">{{ $order->id }}</th>
                                    @if($order->user_status)
                                        <td>@if(isset($order->client)){{ $order->client->name }}@endif</td>
                                    @else
                                        <td>
                                            @if(isset($order->client_not_register)){{ $order->client_not_register->name }}@endif                                            
                                        </td>
                                    @endif                                    
                                    <td>@if(isset($order->course)){!! $order->course->name !!}@endif</td>
                                    <td>{{ $order->getOrderStatus($order->status_id) }}</td>
                                    <td>{{ $order->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ $order->getTypePay($order->type_pay) }}</td>
                                    <td>{{ $order->total }} руб.</td>
                                    <td><a href="{{ route('show_order', $order->id) }}" class="btn btn-light">Просмотр</a></td>                      
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="card-body">Нет заказов!</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-scripts')
    @parent    
@endsection


@section('footer-assets')
    @parent
@endsection