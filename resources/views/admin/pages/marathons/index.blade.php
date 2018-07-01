@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('new_marathon') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>    

    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
        @endif
      @endforeach
    </div> 

    <!-- <div class="jumbotron jumbotron-fluid">
        <form class="form" action="{{ route('marathons_filter') }}" method="get">
            <div class="form-row">
                <div class="col-3">
                    {!! Form::label('name', 'Название марафона' ) !!}
                    {!! Form::text('filter[name]', null, array('class' => 'form-control mb-3', 'id' => 'name' ) ) !!}                    
                </div>                              
            </div>                     
            <button type="submit" class="btn btn-primary mt-3 mb-2">Применить фильтр</button>
            <a href="{{ route('show_marathons') }}" class="btn btn-danger mt-3 mb-2 ml-3">Отменить фильтр</a>
        </form>
    </div> -->

    @if(isset($marathons) && $marathons->count() > 0)

        <div class="card">
            <div class="card-header">                
            </div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название</th>                    
                    <th scope="col">Цена</th>             
                    <th scope="col">Начало отбора</th>
                    <th scope="col">Конец отбора</th>
                    <th scope="col">Период (дней)</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($marathons as $marathon)                
                    <tr>
                        <th scope="row">{{$marathon->id }}</th>
                        <td>{!! $marathon->name !!}</td> 
                        <td>{{ $marathon->price }}</td> 
                        <td>{{ Carbon\Carbon::parse($marathon->date_start_selection)->format('d-m-Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($marathon->date_end_selection)->format('d-m-Y') }}</td>
                        <td>{{ $marathon->period }}</td>                            
                        <td>                           
                            @if(isset($marathon->is_active))
                                {{ $marathon->getCoursStatus($marathon->is_active) }}
                            @endif
                        </td>
                        <td>
                            <ul class="camotek-form-links"> 
                               <li>
                                   <a href="{{ route('course_trainings', $marathon->id) }}" class="btn btn-primary" >Тренировки</a> 
                                </li>                               
                                <li>                                   
                                    <a href="{{ route('edit_marathon', $marathon->id) }}" class="btn btn-primary fa fa-pencil-square-o" data-toggle="tooltip" data-placement="top" title="Редактировать"></a>         
                                </li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_marathon', $marathon->id) }}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />                                       
                                      <!--   <button class="btn btn-danger fa fa-trash-o" type="submit" data-toggle="tooltip" data-placement="top" title="Удалить"></button> -->                                   
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
            Нет марафонов!
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