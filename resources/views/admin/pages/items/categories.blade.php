@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('admin_categories_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($categories) && $categories->count() > 0)
        <div class="card">
            <div class="card-header">Категории</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название категории</th>
                    <th scope="col" class="text-center">Порядок сортировки</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 0; ?>
                @foreach($categories as $category)
                    @if(!isset($category->parent_id))
                        <tr>
                            <th scope="row">{{ ++$index }}</th>
                            <td>@if($category->subcategories->count() > 0)<a href="{{ route('show_category_subcategories', $category->id) }}">{{ $category->locales[0]->name }}@else{{ $category->locales[0]->name }}@endif</a></td>
                            <td class="text-center">{{ $category->sort_order }}</td>
                            <td>{{ $category->created_at->format('d-m-Y') }}</td>
                            <td>{{ $category->is_active ? 'Да' : 'Нет' }}</td>
                            <td>
                                <ul class="camotek-form-links">
                                    <li><a href="{{ route('edit_items_category', $category->id) }}" class="btn btn-primary">Изменить</a></li>
                                    <li>
                                        <form class="delete" action="{{ route('destroy_items_category', $category->id) }}" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <input class="btn btn-danger" type="submit" value="Удалить">
                                        </form>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет категорий!
        </div>
    @endif
@endsection

@section('footer-scripts')
    <script>
        $(document).ready(function () {
          $('.delete').on("submit", function () {
            return confirm('Вы действительно хотите удалить категорию?');
          });
        });
    </script>
@endsection