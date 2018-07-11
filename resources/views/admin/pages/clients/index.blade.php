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
                    {!! Form::label('name', 'Имя клиента или Email' ) !!}
                    {!! Form::text('filter[name]', null, array('class' => 'form-control mb-3', 'id' => 'name' ) ) !!}                    
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
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
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
                        <td>{{ $client->getClientStatus($client->status_id) }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li>
                                    <a href="{{ route('show_client', $client->id) }}" class="btn btn-secondary">Просмотр</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin_results', $client->id) }}" class="btn btn-secondary fa fa-line-chart" data-toggle="tooltip" data-placement="top" title="Просмотр результатов"></a>
                                </li>
                                <li>
                                    @if($client->id != 1)
                                    <a href="{{ route('edit_client', $client->id) }}" class="btn btn-primary fa fa-pencil-square-o" data-toggle="tooltip" data-placement="top" title="Редактировать"></a>
                                    @endif
                                </li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_client', $client->id) }}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        @if($client->id != 1)
                                        <button class="btn btn-danger fa fa-trash-o" type="submit" data-toggle="tooltip" data-placement="top" title="Удалить"></button>
                                        @endif
                                    </form>
                                </li>
                                <li>                                    
                                    <button type="button" class="btn btn-info send-message-user"  data-id-user="{{$client->id}}"><i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="Сбросить пароль и отправить сообщение" style="color: #fff"></i></button>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center">
            {{ $clients->links() }}
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

            $('.send-message-user').on('click', function (e) {
                var recipient_id = $(this).data('idUser');              
                $.ajax({
                    type: "POST",
                    url: '{{ route('send_message_client') }}',
                    data: {
                        user_id:  recipient_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function( msg ) {
                        var $modal = $('#SendMessageModal');
                        var resultMessageField = $modal.find('.result-message');             
                        resultMessageField.text(msg.result);
                        $('#SendMessageModal').modal('show');                        
                    },
                    error: function (xhr, b, c) {
                        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                    }
                });
            });       
        });
        
    </script>

@endsection

@section('footer-modal')
<!-- Modal -->
<div class="modal" tabindex="-1" role="dialog" id="SendMessageModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="result-message"></p>
      </div>      
    </div>
  </div>
</div>
@endsection