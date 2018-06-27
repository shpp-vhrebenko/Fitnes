@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('categories_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
        @endif
      @endforeach
    </div>    
    @if(isset($categories) && $categories->count() > 0)
        <div class="card">
            <div class="card-header">Категории</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название категории</th>                    
                    <th scope="col">Дата создания</th>    
                    <th scope="col">Действия</th>                
                </tr>
                </thead>
                <tbody>
                <?php $index = 0; ?>
                @foreach($categories as $category)                    
                    <tr>
                        <th scope="row">{{ ++$index }}</th>
                        <td>{{ $category->name }}</td>               
                        <td>{{ $category->created_at->format('d-m-Y') }}</td>                
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_category', $category->id) }}" class="btn btn-primary">Изменить</a></li>
                                @if($category->id != 1)
                                <li>
                                    <form class="delete" action="{{ route('destroy_category', $category->id) }}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input class="btn btn-danger" type="submit" value="Удалить">
                                    </form>
                                </li>
                                @endif
                            </ul>
                        </td>
                    </tr>
                    
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center">
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