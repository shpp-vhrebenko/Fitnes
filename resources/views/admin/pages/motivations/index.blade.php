@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('motivations_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
        @endif
      @endforeach
    </div>

    @if(isset($motivations) && $motivations->count() > 0)
        <div class="card">
            <div class="card-header">Записи</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Тест мотиватора</th>  
                    <th scope="col">Действия</th>            
                </tr>
                </thead>
                <tbody>
                @foreach($motivations as $item)
                    <tr>
                        <th scope="row">{{ $item->id }}</th>                       
                        <td>
                            @if(isset($item->text))
                                {!! $item->text !!}
                            @endif
                        </td>                                             
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('motivations_edit', $item->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('motivations_destroy', $item->id) }}" method="POST">
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
        <div class="row justify-content-center">
            {{ $motivations->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет записей мотиватора!
        </div>
    @endif
@endsection

@section('footer-scripts')
    @parent
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить Мотиватор?');
        });
      });
    </script>
@endsection