@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('new_cours') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>    

    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
        @endif
      @endforeach
    </div>    

    @if(isset($trainings) && count($trainings) > 0)
        <div class="row">
            @foreach ( $trainings as $key => $value )
                <div class="col-md-3" style="@if($value['is_holiday']) background-color: red; @else background-color: green @endif">
                    <h5>{{ str_replace('day_','',$key) }}</h5> 
                    <p>{{$value['item_id']}}</p>
                    <p>{{$value['is_holiday']}}</p>
                    @if($value['item_id'] == 0)
                        <a href="{{route('new_training',['id_course' => $id_course, 'number_day' => str_replace('day_','',$key)] ) }}" class="btn btn-primary">Создать</a>
                    @endif
                    @if($value['item_id'] != 0)
                         <a href="{{route('edit_training', $value['item_id'])}}" class="btn btn-primary">Редактировать</a>
                    @endif     
                </div>                   
            @endforeach
        </div>         
    @else
        <div class="alert alert-danger" role="alert">
            Нет тренировок!
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