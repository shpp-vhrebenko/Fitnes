@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('page_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($pages) && $pages->count() > 0)
    <div class="card">
    <div class="card-header">Страницы</div>
    <table class="table camotek-admin-table">
    <thead>
    <tr>
    <th scope="col">ID</th>
    {{--<th scope="col">Превью</th>--}}
    <th scope="col">Название страницы</th>
    <th scope="col">Дата создания</th>
    <th scope="col">Активация</th>
    <th scope="col">Действие</th>
    </tr>
    </thead>
        <tbody>
        <?php $index = 0; ?>
        @foreach($pages as $page)
            <tr>
                <th scope="row">{{ ++$index }}</th>
                {{--<td><img src="#" /></td>--}}
                <td>{{ $page->locales[0]->name }}</td>
                <td>{{ $page->created_at->format('d-m-Y') }}</td>
                <td>{{ $page->is_active ? 'Да' : 'Нет' }}</td>
                <td>
                    <ul class="camotek-form-links">
                        <li><a href="{{ route('edit_page', $page->id) }}" class="btn btn-primary">Изменить</a></li>
                        <li>
                            <form class="delete" action="{{ route('destroy_page', $page->id) }}" method="POST">
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
    </div>
    @else
    <div class="alert alert-danger" role="alert">
    Нет страниц!
    </div>
    @endif
@endsection

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить страницу?');
        });
      });
    </script>
@endsection