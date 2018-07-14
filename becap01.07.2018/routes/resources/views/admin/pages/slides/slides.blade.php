@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('slide_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($slides) && $slides->count() > 0)
        <div class="card">
            <div class="card-header">Слайды</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название слайда</th>
                    <th scope="col">Ссылка</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($slides as $slide)
                    <tr>
                        <th scope="row">{{ $slide->id }}</th>
                        <td>{{ $slide->locales[0]->name }}</td>
                        <td>@if(isset($slide->locales[0]->link)) {{ $slide->locales[0]->link }} @endif</td>
                        <td>{{ $slide->created_at->format('d-m-Y') }}</td>
                        <td>{{ $slide->created_at->format('d-m-Y') }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_slide', $slide->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_slide', $slide->id) }}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input class="btn btn-danger" type="submit" value="Удалить">
                                    </form>
                                </li>
                            </ul>
                        </td>
                        {{--<td><a href="{{ route('show_slide', $slide->id) }}" class="btn btn-primary">Просмотр слайда</a></td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет слайдов!
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