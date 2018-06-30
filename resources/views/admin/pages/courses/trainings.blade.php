@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>        
    </div>    

    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
        @endif
      @endforeach
    </div>    

    @if(isset($trainings) && count($trainings) > 0)
        <div class="row trainings">
            @foreach ( $trainings as $key => $value )
                <div class="col-md-3">
                    <div class=" trainings__item card" style="
                        @if(isset($value['image']))
                            background-image: url(/uploads/items/{{$value['image']}});
                        @else
                            background-image: url(/uploads/items/no-image.png);
                        @endif ">
                        <div class="trainings__header">
                            <h4>{{$value['title']}}</h4>
                            <p>День: {{ str_replace('day_','',$key) }}</p>  
                        </div>                                               
                        @if($value['item_id'] == 0)
                            <a href="{{route('new_training',['id_course' => $id_course, 'number_day' => str_replace('day_','',$key)] ) }}" class="btn btn-success fa fa-plus btn-add-training" data-toggle="tooltip" data-placement="top" title="Создать тренировку"></a>
                        @endif
                        @if($value['item_id'] != 0)
                             <a href="{{route('edit_training', $value['item_id'])}}" class="btn btn-primary fa fa-pencil btn-edit-training" data-toggle="tooltip" data-placement="top" title="Редактировать тренировку"></a>
                        @endif
                        <div class="trainings-status @if($value['item_id'] == 0) 
                                trainings__item-empty                   
                            @else
                                @if($value['is_holiday'])
                                    trainings__item-holiday                                
                                @endif
                            @endif   ">
                            @if($value['item_id'] == 0) 
                                Нет тренировки                  
                            @else
                                @if($value['is_holiday'])
                                    Выходной день                               
                                @endif
                            @endif   
                        </div>  
                    </div>
                        
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
              return confirm('Вы действительно хотите удалить тренировку?');
            });           
        });
        
    </script>
@endsection

@section('footer-modal')
@endsection