@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('admin_items_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="jumbotron jumbotron-fluid">
        <form class="form" action="{{ route('items_filter') }}" method="get">
            <div class="form-row">
                {!! input_generate('col-4', 'Название товара', 'text', null, 'form-control mb-3', null, 'name') !!}
                {!! input_generate('col-2', 'Цена от', 'text', \App\Role::all_roles(), 'form-control mb-3', null, 'price_min') !!}
                {!! input_generate('col-2', 'Цена до', 'text', null, 'form-control mb-3', null, 'price_max') !!}
                {!! input_generate('col-4', 'Дата добавления', 'date', null, 'form-control mb-3', null, 'created_at') !!}
            </div>
            <div class="form-row">
                {!! input_generate('col-4', 'Код товара', 'text', null, 'form-control mb-3', null, 'code') !!}
                {!! input_generate('col-2', 'Количество от', 'text', \App\Role::all_roles(), 'form-control mb-3', null, 'qty_min') !!}
                {!! input_generate('col-2', 'Количество до', 'text', null, 'form-control mb-3', null, 'qty_max') !!}
                {!! input_generate('col-4', 'Категория товара', 'select', \App\Role::all_roles(), 'form-control mb-2', null, 'category_id') !!}
            </div>
            <div class="form-row">
                {!! input_generate('col-4', 'Фильтр', 'select', \App\Role::all_roles(), 'form-control mb-2', null, 'filter_id') !!}
                {!! input_generate('col-4', 'Технология', 'select', \App\Role::all_roles(), 'form-control mb-2', null, 'technology_id') !!}
                {!! input_generate('col-4', 'Дата изменения', 'date', null, 'form-control mb-3', null, 'updated_at') !!}
            </div>
            <button type="submit" class="btn btn-primary mt-3 mb-2">Применить фильтр</button>
            <a href="{{ route('admin_items') }}" class="btn btn-danger mt-3 mb-2 ml-3">Отменить фильтр</a>
        </form>
    </div>


    @if(isset($items) && $items->count() > 0)
        <div class="card">
            <div class="card-header">Товары</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Фото</th>
                    <th scope="col">Название товара</th>
                    <th class="text-center" scope="col">Код товара</th>
                    <th scope="col">Категория</th>
                    <th scope="col">Цена</th>
                    <th class="text-center" scope="col">Кол-во</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <th scope="row">{{ $item->id }}</th>
                        <td>
                            @if(isset($item->preview))<img src="{{ asset($item->preview->path) }}" style="width: 50px;" />@endif
                        </td>
                        <td>@if(isset($item->locales))<a href="{{ route('item', $item->locales[0]->slug) }}" target="_blank">{{ $item->locales[0]->name }}</a>@endif</td>
                        <td class="text-center">{{ $item->code }}</td>
                        <td>
                            @foreach($item->categories as $key => $category)
                                {{ $category->locales[0]->name }}@if($key < count($item->categories) - 1),@endif
                                @endforeach
                        </td>
                        <td>{{ $item->price }}</td>
                        <td class="text-center">
                            @if($item->qty > 1)
                                <span class="badge badge-success">{{ $item->qty }}</span>
                            @elseif($item->qty == 1)
                                <span class="badge badge-warning" style="color: #fff;">{{ $item->qty }}</span>
                            @else
                                <span class="badge badge-danger">{{ $item->qty }}</span>
                            @endif
                        </td>
                        <td>{{ $item->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_item', $item->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_item', $item->id) }}" method="POST">
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
            {{ $items->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет товаров!
        </div>
    @endif
@endsection

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить товар?');
        });
      });
    </script>
@endsection