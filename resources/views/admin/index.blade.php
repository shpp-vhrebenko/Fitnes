@extends('admin/layout/admin')

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