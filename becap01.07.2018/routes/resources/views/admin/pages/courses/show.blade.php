@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{!! $title !!}</h1></div>        
    </div> 
	@if(isset($course))
        <div class="card">
            <div class="card-header">                
            </div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название</th>                    
                    <th scope="col">Цена</th> 
                    <th scope="col">Период (дней)</th>                   
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>                
                <tr>
                    <th scope="row">{{ $course->id }}</th>
                    <td>{!! $course->name !!}</td> 
                    <td>{{ $course->price }}</td>   
                    <td>{{ $course->period }}</td>                           
                    <td>{{$course->getCoursStatus($course->is_active)}}                        
                    </td>
                    <td>
                        <ul class="camotek-form-links">
                            <li>
                               <a href="{{ route('course_trainings', $course->id) }}" class="btn btn-primary" >Тренировки</a> 
                            </li>                                    
                            <li>                                   
                                <a href="{{ route('edit_cours', $course->id) }}" class="btn btn-primary fa fa-pencil-square-o" data-toggle="tooltip" data-placement="top" title="Редактировать"></a>         
                            </li>
                            <li>
                                <form class="delete" action="{{ route('destroy_cours', $course->id) }}" method="POST">
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