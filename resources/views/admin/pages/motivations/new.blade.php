@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }} <a href="{{ route('motivations') }}" class="fa fa-arrow-left btn-back" data-toggle="tooltip" data-placement="top" title="Вернутся назад"></a></h1>
    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
        @endif
      @endforeach
    </div>  
    @if($errors)
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="card">
        <form @if(Route::is('motivations_edit')) action="{{ route('motivations_update', $item->id) }}" @else action="{{ route('motivations_store') }}" @endif method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
            @if(Route::is('motivations_edit')) <input type="hidden" name="_method" value="put"> @endif
            @if(Route::is('motivations_edit')) <input type="hidden" name="item_id" value="{{ $item->id }}"> @endif
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Общие</a>
                </li>                           
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="base" role="tabpanel" aria-labelledby="base-tab">
                    <div class="row">                      
                        <div class="col-12">                                                     
                            <div class="form-group row">
                                <label for="text" class="col-sm-2 col-form-label">Контент записи<sup class="required">*</sup></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="text" name="item[text]" rows="3" id="text">{{ isset($item) ? $item->text : old('item.text') }}</textarea>
                                </div>
                            </div>                                     
                        </div>
                    </div>
                </div>                             
        </div>
        <div class="card-footer clearfix">
            <a href="{{ route('motivations') }}" class="btn btn-secondary pull-left cancel">Отменить</a>
            <button type="submit" class="btn btn-success pull-right">@if(Route::is('motivations_edit')) Изменить @else Создать @endif</button>
        </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    @parent    
    <script>
    $(document).ready(function () {
        $('.cancel').on("click", function () {
          return confirm('Вы действительно хотите отменить?');
        });        
    });
    </script>
@endsection