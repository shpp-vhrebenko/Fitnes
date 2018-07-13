@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('technology_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($technologies) && $technologies->count() > 0)
        <div class="card">
            <div class="card-header">Технологии</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Иозбражение</th>
                    <th scope="col" class="fixed-width-250">Название технологии</th>
                    <th scope="col">Категория</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Дата изменения</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($technologies as $technology)
                    <tr>
                        <th scope="row">{{ $technology->id }}</th>
                        <td>@if(isset($technology->preview))<img src="{{ asset($technology->preview->path) }}" style="width: 50px;" />@endif</td>
                        <td class="fixed-width-250">{{ isset($technology->locales) && count($technology->locales) ? str_limit($technology->locales[0]->name, 50, '...') : '-' }}</td>
                        <td>
                            <a href="{{ route('technologies_categories_edit', $technology->category->id ) }}">{{ isset($technology->category->locales) ? $technology->category->locales[0]->name : '-' }}</a>
                        </td>
                        <td>{{ $technology->created_at->format('d-m-Y') }}</td>
                        <td>{{ $technology->updated_at->format('d-m-Y') }}</td>
                        <td>{{ $technology->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('technology_edit', $technology->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('technology_destroy', $technology->id) }}" method="POST">
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
            {{ $technologies->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет технологий!
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