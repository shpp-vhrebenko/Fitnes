@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{!! $title !!}</h1></div>        
    </div>        

    @if(isset($marathon))

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
                </tbody>
            </table>
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