@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('technologies_categories_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($categories) && $categories->count() > 0)
        <div class="card">
            <div class="card-header">Технологии</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col" class="fixed-width-250">Название категории</th>
                    <th scope="col">Категория</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Дата изменения</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <th scope="row">{{ $category->id }}</th>
                        <td class="fixed-width-250">{{ str_limit($category->locales[0]->name, 50, '...') }}</td>
                        <td>
                            {{ isset($category->category->locales) ? $category->category->locales[0]['name'] : '-' }}
                        </td>
                        <td>{{ $category->created_at->format('d-m-Y') }}</td>
                        <td>{{ $category->updated_at->format('d-m-Y') }}</td>
                        <td>{{ $category->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('technologies_categories_edit', $category->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('technologies_categories_destroy', $category->id) }}" method="POST">
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
            {{ $categories->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет категорий!
        </div>
    @endif
@endsection