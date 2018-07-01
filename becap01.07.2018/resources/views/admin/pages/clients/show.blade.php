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
                            <th scope="col">Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>                            
                            <td>{{ $client->getClientStatus($client->status_id) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </section>

@endsection