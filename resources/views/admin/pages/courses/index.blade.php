@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('new_cours') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>    

    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="jumbotron jumbotron-fluid">
        <form class="form" action="{{ route('courses_filter') }}" method="get">
            <div class="form-row">
                <div class="col-3">
                    {!! Form::label('name', 'Название курса' ) !!}
                    {!! Form::text('filter[name]', null, array('class' => 'form-control mb-3', 'id' => 'name' ) ) !!}                    
                </div>                              
            </div>                     
            <button type="submit" class="btn btn-primary mt-3 mb-2">Применить фильтр</button>
            <a href="{{ route('clients') }}" class="btn btn-danger mt-3 mb-2 ml-3">Отменить фильтр</a>
        </form>
    </div>

    @if(isset($courses) && $courses->count() > 0)
        <div class="card">
            <div class="card-header">                
            </div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название</th>                    
                    <th scope="col">Цена</th>                    
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $cours)
                    <tr>
                        <th scope="row">{{ $cours->id }}</th>
                        <td>{{ $cours->name }}</td> 
                        <td>{{ $cours->price }}</td>                              
                        <td>
                            @if($cours->is_active)
                            Активный 
                            @else
                            Не активный 
                            @endif
                        </td>
                        <td>
                            <ul class="camotek-form-links">
                                <li>
                                    <a href="{{ route('show_cours', $cours->id) }}" class="btn btn-secondary">Просмотр</a>
                                </li>                                
                                <li>                                   
                                    <a href="{{ route('edit_cours', $cours->id) }}" class="btn btn-primary">Редактировать</a>         
                                </li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_cours', $cours->id) }}" method="POST">
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
            Нет курсов!
        </div>
    @endif
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function () {
            $('.delete').on("submit", function () {
              return confirm('Вы действительно хотите удалить курс?');
            });           
        });
        
    </script>
@endsection

@section('footer-modal')
@endsection