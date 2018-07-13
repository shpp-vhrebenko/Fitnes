@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('blog_articles_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($articles) && $articles->count() > 0)
        <div class="card">
            <div class="card-header">Статьи</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Иозбражение</th>
                    <th scope="col" class="fixed-width-250">Название материала</th>
                    <th scope="col">Категория</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Дата изменения</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($articles as $article)
                    <tr>
                        <th scope="row">{{ $article->id }}</th>
                        <td>@if(isset($article->preview))<img src="{{ asset($article->preview->path) }}" style="width: 50px;" />@endif</td>
                        <td class="fixed-width-250">{{ str_limit($article->locales[0]->name, 50, '...') }}</td>
                        <td>
                            <?php $cat_index = 0; ?>
                            @foreach($article->categories as $category)
                                    <a href="{{ route('edit_blog_category', $category->id ) }}">{{ $category->locales[0]->name }}</a>
                                @if(++$cat_index < $article->categories->count()),<br/>@endif
                            @endforeach
                        </td>
                        <td>{{ $article->created_at->format('d-m-Y') }}</td>
                        <td>{{ $article->updated_at->format('d-m-Y') }}</td>
                        <td>{{ $article->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_blog_article', $article->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_blog_article', $article->id) }}" method="POST">
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
            {{ $articles->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет статей!
        </div>
    @endif
@endsection

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить статью?');
        });
      });
    </script>
@endsection