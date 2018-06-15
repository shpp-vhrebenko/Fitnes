@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('table_sizes_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>

    @if(isset($tables) && $tables->count() > 0)
        <div class="card">
            <div class="card-header">Таблицы размеров</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Просмотр</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tables as $table)
                    <tr>
                        <th scope="row">{{ $table->id }}</th>
                        <td>@if(isset($table->locales)) {{ $table->locales[1]->name }} @endif</td>
                        <td>{{ $table->created_at->format('d-m-Y') }}</td>
                        <td>{{ $table->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_table', $table->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_table', $table->id) }}" method="POST">
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
            {{ $tables->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет размерных таблиц!
        </div>
    @endif
@endsection