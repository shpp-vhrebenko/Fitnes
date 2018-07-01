@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($clients) && $clients->count() > 0)
        <div class="card">
            <div class="card-header">Аквтивность клиентов</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">Имя покупателя</th>
                    <th scope="col">E-Mail</th>
                    <th scope="col">Группа покупателей</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Кол-во заказов</th>
                    <th scope="col">Итого</th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->roles[0]->name }}</td>
                        <td>{{ $client->getClientStatus($client->status_id) }}</td>
                        <td>{{ $client->orders->count() }}</td>
                        <td>{{ $client->orders->sum('total') }} грн.</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $clients->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет пользователей!
        </div>
    @endif
@endsection