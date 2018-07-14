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
                <th scope="col">Статус</th>
                <th scope="col">Дата заказа</th>
                <th scope="col">Всего</th>                
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">{{ $order->id }}</th>
                @if($order->user_status)
                    <td>@if(isset($order->client))<a href="{{ route('show_client', $order->client->id) }}">{{ $order->client->name }}</a>@endif
                    </td>
                @else                            
                    <td>@if(isset($order->client_not_register)) 
                        <a href="{{ route('show_client_not_register', $order->client_not_register->id) }}">{{ $order->client_not_register->name }}</a>
                        @endif
                    </td>
                @endif                               
                <td>{{ $order->getOrderStatus($order->status_id) }}</td>
                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                <td>{{ $order->total }} руб.</td>                
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
                <th scope="col">Статус клиента</th>
                <th scope="col">Почта</th>
                <th scope="col">Телефон</th>
            
            </tr>
            </thead>
            <tbody>
            <tr>
                @if($order->user_status)
                    <td>@if(isset($order->client))<a href="{{ route('show_client', $order->client->id) }}">{{ $order->client->name }}</a> @endif</td>
                    <td>{{ $order->getClientStatus($order->user_status) }}</td>              
                    <td>@if(isset($order->client)){{ $order->client->email }} @endif</td>
                    <td>@if(isset($order->client)){{ $order->client->phone }} @endif</td>   
                @else
                    <td>@if(isset($order->client_not_register))<a href="{{ route('show_client_not_register', $order->client_not_register->id) }}">{{ $order->client_not_register->name }}</a> @endif</td>
                    <td>{{ $order->getClientStatus($order->user_status) }}</td>              
                    <td>@if(isset($order->client_not_register)){{ $order->client_not_register->email }} @endif</td>
                    <td>@if(isset($order->client_not_register)){{ $order->client_not_register->phone }} @endif</td>  
                @endif              
            </tr>
            </tbody>
        </table>
    </div>

    <br/>

    @if(isset($course))
        <div class="card">
            <div class="card-header">Заказаный Курс</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название курса</th>                    
                    <th scope="col">Цена</th>                    
                </tr>
                </thead>
                <tbody>
                
                <tr>
                    <th scope="row">{{ $course->id }}</th>
                    <td><a href="@if($course->type == 'cours'){{route('show_cours', $course->id)}}@else{{ route('show_marathon', $course->id) }}@endif">{!! $course->name !!}</a></td>                   
                    <td>{{ $course->price }} руб.</td>                    
                </tr>
                
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет заказов!
        </div>
    @endif

@endsection