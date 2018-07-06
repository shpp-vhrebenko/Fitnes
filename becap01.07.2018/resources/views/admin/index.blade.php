@extends('admin/layout/admin')

@section('header-styles')     
    @parent         
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')
    <section class="camotek-admin-hero-stats">
        <div class="row">           
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        Количество клиентов
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $stats['users'] }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        Количество заказов
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $stats['orders'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>    
    <section class="camotek-admin-index-modules">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Последние заказы
                    </div>
                    @if(isset($latest_orders) && $latest_orders->count() > 0)
                        <table class="table camotek-admin-table">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Клиент</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Дата заказа</th>
                                <th scope="col">Всего</th>                                
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($latest_orders as $order)
                                <tr>
                                    <th scope="row">{{ $order->id }}</th>
                                    <td>@if(isset($order->client))<a href="{{ route('show_client', $order->client->id) }}">{{ $order->client->name }}</a>@endif</td>
                                    <td>{{ $order->getOrderStatus($order->status_id) }}</td>
                                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $order->total }} рубл.</td>                      
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="card-body">Нет заказов!</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-scripts')
    @parent    
@endsection


@section('footer-assets')
    <!-- include summernote css/js -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/datepicker.min.js') }}"></script>

<style>
  .dropdown:hover>.dropdown-menu {
    display: block;
  }
</style>

<script>
  $(document).ready(function() {
    $('.summernote').summernote({
      height: 200
    });
    $('[data-toggle="datepicker"]').datepicker({
      format: 'yyyy-mm-dd'
    });
  });
</script>
@endsection