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
        </div>
    </section>
    <section class="camotek-admin-index-modules">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Google Аналитика
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">код гугла</h6>
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
                    <div class="card-body">Нет заказов!</div>                    
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