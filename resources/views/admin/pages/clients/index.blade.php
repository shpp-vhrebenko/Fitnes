@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>

    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="jumbotron jumbotron-fluid">
        <form class="form" action="{{ route('clients_filter') }}" method="get">
            <div class="form-row">
                {!! input_generate('col-3', 'Имя покупателя', 'text', null, 'form-control mb-3', null, 'name') !!}
                {!! input_generate('col-3', 'Группа покупателей', 'select', \App\Role::all_roles(), 'form-control mb-3', null, 'role_id') !!}
                {!! input_generate('col-3', 'Одобрить', 'text', null, 'form-control mb-3', null, '???') !!}
                {!! input_generate('col-3', 'Дата добавления', 'date', null, 'form-control mb-3', null, 'created_at') !!}
            </div>
            <div class="form-row">
                {!! input_generate('col-3', 'E-Mail', 'text', null, 'form-control mb-2', null, 'email') !!}
                {!! input_generate('col-3', 'Статус', 'select', \App\User::getUserStatus(), 'form-control mb-2', null, 'status_id') !!}
                {!! input_generate('col-3', 'IP', 'text', null, 'form-control mb-2', null, 'ip') !!}
                {!! input_generate('col-3', 'Дата изменения', 'date', null, 'form-control mb-2', null, 'updated_at') !!}
            </div>
            <div class="form-row">
                {!! input_generate('col-3', 'Номер телефона', 'text', null, 'form-control mb-2', null, 'telephone') !!}
            </div>
            <button type="submit" class="btn btn-primary mt-3 mb-2">Применить фильтр</button>
            <a href="{{ route('clients') }}" class="btn btn-danger mt-3 mb-2 ml-3">Отменить фильтр</a>
        </form>
    </div>

    @if(isset($clients) && $clients->count() > 0)
        <div class="card">
            <div class="card-header">Клиенты</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Клиент</th>
                    <th scope="col">Почта</th>
                    <th scope="col">Роль</th>
                    <th scope="col">IP</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Просмотр</th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <th scope="row">{{ $client->id }}</th>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>
                            @foreach($client->roles as $role)
                                {{ $role->name }}
                            @endforeach
                        </td>
                        <td>{{ $client->ip }}</td>
                        <td>{{ $client->getClientStatus($client->status_id) }}</td>
                        <td>
                            <ul class="camotek-form-links">
                            <li><a href="{{ route('show_client', $client->id) }}" class="btn btn-light">Просмотр</a></li>
                                <li><a href="{{ route('edit_client', $client->id) }}" class="btn btn-primary">Редактировать</a></li>
                                <li><form class="delete" action="{{ route('destroy_client', $client->id) }}" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input class="btn btn-danger" type="submit" value="Удалить">
                            </form></li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет клиентов!
        </div>
    @endif
@endsection

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить пользователя и его заказы?');
        });
      });
    </script>
@endsection