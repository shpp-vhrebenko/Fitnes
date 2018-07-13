@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('items_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
        @endif
      @endforeach
    </div> 
    <div class="jumbotron jumbotron-fluid">
        <form class="form" action="{{ route('items_filter') }}" method="get">
            <div class="form-row">
                <div class="col-3">
                    <label for="title">Название записи</label>
                    <input type="text" class="form-control mb-3" placeholder="" name="filter[title]" id="title">
                </div>
                <div class="col-3">
                    <label for="categoryId">Категория</label>
                    <select class="form-control" id="categoryId" name="filter[category_id]">                        
                        @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $category) 
                                @if($category->id != 1)                                       
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>                    
                </div>
                <div class="col-3">
                    <label for="courseId">Название курса или марафона</label>
                    <select class="form-control" id="courseId" name="filter[course_id]">       
                        @if(isset($courses) && count($courses) > 0)
                            @foreach($courses as $course)                                        
                                <option value="{{ $course->id }}">{!! $course->name !!}</option>
                            @endforeach
                        @endif        
                    </select>            
                </div>   
                       
            </div>                       
            <button type="submit" class="btn btn-primary mt-3 mb-2">Применить фильтр</button>
            <a href="{{ route('admin_items') }}" class="btn btn-danger mt-3 mb-2 ml-3">Отменить фильтр</a>
        </form>
    </div>


    @if(isset($items) && $items->count() > 0)
        <div class="card">
            <div class="card-header">Записи</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Изображение</th>
                    <th scope="col">Заголовок записи</th>   
                    <th scope="col">Статус</th>                 
                    <th scope="col">Категория</th>                   
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <th scope="row">{{ $item->id }}</th>
                        <td>
                            @if(isset($item->image))
                                <div class="item-preview" style="background-image: url('/uploads/items/{{ $item->image }}');">
                                </div>    
                            @endif
                        </td> 
                        <td>
                            @if(isset($item->title))
                                {{ $item->title }}
                            @endif
                        </td> 
                        <td>
                            @if(isset($item->is_active))
                                {{ $item->getItemStatus($item->is_active) }}
                            @endif
                        </td>                        
                        <td>
                            @if(isset($item->category_id))
                                                                 
                                {{  $item->category->name }}                               
                            @endif    
                        </td>                       
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_item', $item->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_item', $item->id) }}" method="POST">
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
            {{ $items->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет записей!
        </div>
    @endif
@endsection

@section('footer-scripts')
    @parent
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить запись?');
        });
      });
    </script>
@endsection