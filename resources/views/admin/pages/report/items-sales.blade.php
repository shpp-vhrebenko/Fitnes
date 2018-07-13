@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($items) && $items->count() > 0)
        <div class="card">
            <div class="card-header">Просмотры товаров</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">Название товара</th>
                    <th scope="col">Код товара</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Кол-во продаж</th>
                    <th scope="col">Итого</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->locales[0]->name }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td>{{ $item->sales }}</td>
                        <td>{{ $item->sales * $item->price }} грн.</td>
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