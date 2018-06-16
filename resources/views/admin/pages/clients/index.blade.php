@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>

    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="jumbotron jumbotron-fluid">
        <form class="form" action="{{ route('clients_filter') }}" method="get">
            <div class="form-row">
                <div class="col-3">
                    {!! Form::label('name', 'Имя клиента' ) !!}
                    {!! Form::text('filter[name]', null, array('class' => 'form-control mb-3', 'id' => 'name' ) ) !!}                    
                </div>
                <div class="col-3">
                    {!! Form::label('created_at', 'Дата добавления' ) !!}
                    {!! Form::date('filter[created_at]', null, array('class' => 'form-control mb-3', 'id' => 'created_at' ) ) !!}                    
                </div>
                <div class="col-3">
                    {!! Form::label('updated_at', 'Дата изменения' ) !!}
                    {!! Form::date('filter[updated_at]', null, array('class' => 'form-control mb-3', 'id' => 'updated_at' ) ) !!}                    
                </div>               
            </div>
            <div class="form-row">
                <div class="col-3">
                    {!! Form::label('status_id', 'Статус пользователя' ) !!}
                    {!! Form::select('filter[status_id]', \App\User::getUserStatus() , array('class' => 'form-control mb-3', 'id' => 'status_id' ) ) !!}                    
                </div> 
                <div class="col-3">
                    {!! Form::label('email', 'E-mail' ) !!}
                    {!! Form::email('filter[email]', null, array('class' => 'form-control mb-3', 'id' => 'email' ) ) !!}                    
                </div>   
                <div class="col-3">
                    {!! Form::label('phone', 'Номер телефона' ) !!}
                    {!! Form::text('filter[phone]', null, array('class' => 'form-control mb-3', 'id' => 'phone' ) ) !!}                    
                </div>                
            </div>            
            <button type="submit" class="btn btn-primary mt-3 mb-2">Применить фильтр</button>
            <a href="{{ route('clients') }}" class="btn btn-danger mt-3 mb-2 ml-3">Отменить фильтр</a>
        </form>
    </div>

    @if(isset($clients) && $clients->count() > 0)
        <div class="card">
            <div class="card-header">
                <span class="pull-left">Клиенты</span>
                <a href="{{ route('new_client') }}" class="btn btn-success pull-right">Создать клиента</a>
            </div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Клиент</th>
                    <th scope="col">Почта</th>
                    <th scope="col">Роль</th>
                    <th scope="col"></th>
                    <th scope="col">Статус</th>
                    <th scope="col">Просмотр</th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <th scope="row">{{ $client->id }}</th>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>                            
                            @foreach($client->roles as $role)
                                {{ $role->name }}
                            @endforeach
                        </td>
                        <td></td>
                        <td>{{ $client->getClientStatus($client->status_id) }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li>
                                    <a href="{{ route('show_client', $client->id) }}" class="btn btn-light">Просмотр</a></li>
                                <li>
                                    <a href="{{ route('edit_client', $client->id) }}" class="btn btn-primary">Редактировать</a>
                                </li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_client', $client->id) }}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input class="btn btn-danger" type="submit" value="Удалить">
                                    </form>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#SendMessageModal" data-id-user="{{$client->id}}"><i class="fa fa-envelope" style="color: #fff"></i></button>
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
            Нет клиентов!
        </div>
    @endif
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function () {
            $('.delete').on("submit", function () {
              return confirm('Вы действительно хотите удалить пользователя и его заказы?');
            });

            $('#SendMessageModal').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget);
              var recipient = button.data('idUser');
              console.log(recipient);
              var modal = $(this);
              var $inputUserId = modal.find('#user_id');
              $inputUserId.attr('value',recipient);          
            })
        });
        
    </script>

@endsection

@section('footer-modal')
<!-- Modal -->
<div class="modal fade" id="SendMessageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="modal-content" action="{{ route('send_message_client') }}" method="POST"> 
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Отправить сообщение клиенту</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <input type="hidden" name="_token" value="{{ csrf_token() }}" />
         <input type="hidden" name="user_id" id="user_id">
         <div class="form-group ">
            <label for="messageUser">Текст сообщения</label>
            <textarea class="form-control" id="messageUser" rows="4" name="messageUser"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" type="reset">Очистить</button>
        <button type="submit" class="btn btn-success">Отправить</button>
      </div>
    </form>
  </div>
</div>
@endsection