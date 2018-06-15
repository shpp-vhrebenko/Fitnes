@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('admin_reviews_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($reviews) && $reviews->count() > 0)
        <div class="card">
            <div class="card-header">Отзывы</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Клиент</th>
                    <th scope="col">Товар</th>
                    <th scope="col">Рейтинг</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Время</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Просмотр</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <th scope="row">{{ $review->id }}</th>
                        <td><a href="{{ route('show_client', $review->client->id) }}">{{ $review->client->name }}</a></td>
                        <td>{{ $review->item->locales[0]->name }}</td>
                        <td>{{ $review->rating }}</td>
                        <td>{{ $review->created_at->format('d-m-Y') }}</td>
                        <td>{{ $review->created_at->format('h:i:s') }}</td>
                        <td>{{ $review->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_review', $review->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_review', $review->id) }}" method="POST">
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
            {{ $reviews->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет отзывов!
        </div>
    @endif
@endsection

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить отзыв?');
        });
      });
    </script>
@endsection