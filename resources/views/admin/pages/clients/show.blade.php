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
                            <th scope="col">Курс</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            @if($client_status)
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>                            
                            <td>{{ $client->getClientStatus($client->status_id) }}</td>
                            <td><a href="@if($course->type == 'cours'){{route('show_cours', $course->id)}}@else{{ route('show_marathon', $course->id) }}@endif">{!! $course->name !!}</a></td>
                            @else
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>                            
                            <td>Клиент {{ $client->name }} <span class="badge badge-info">не зарегистрирован</span></td>
                            <td>Курс не оплачен</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </section>

@endsection