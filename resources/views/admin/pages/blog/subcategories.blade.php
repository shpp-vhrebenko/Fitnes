@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>
    @if(isset($categories) && $categories->count() > 0)
        <div class="card">
            <div class="card-header">Подкатегории</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название категории</th>
                    <th scope="col" class="text-center">Порядок сортировки</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Просмотр</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 0; ?>
                @foreach($categories as $category)
                    <tr>
                        <th scope="row">{{ ++$index }}</th>
                        <td>@if($category->subcategories->count() > 0)<a href="{{ route('show_category_subcategories', $category->id) }}">{{ $category->locales[0]->name }}@else{{ $category->locales[0]->name }}@endif</a></td>
                        <td class="text-center">{{ $category->sort_order }}</td>
                        <td>{{ $category->created_at->format('d-m-Y') }}</td>
                        <td>{{ $category->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_blog_category', $category->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_blog_category', $category->id) }}" method="POST">
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

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить категорию?');
        });
      });
    </script>
@endsection