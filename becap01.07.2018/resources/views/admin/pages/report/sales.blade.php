@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($sales) && $sales['sales'] > 0)
        <div class="card">
            <div class="card-header">Продажи</div>
            <table class="table camotek-admin-table">
                <thead>
                    <tr>
                        <th scope="col">Дата начала</th>
                        <th scope="col">Дата окончания</th>
                        <th scope="col">Кол-во заказов</th>
                        <th scope="col">Налог</th>
                        <th scope="col">Итого</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $sales['first_day']->format('d-m-Y') }}</td>
                        <td>{{ $sales['last_day']->format('d-m-Y') }}</td>
                        <td>{{ $sales['sales_month']->count() }}</td>
                        <td>0 грн.</td>
                        <td>{{ $sales['total_sales_month'] }} грн.</td>
                    </tr>
                    <tr>
                        <td>{{ $sales['last_ten_days']->format('d-m-Y') }}</td>
                        <td>{{ $sales['last_ten_days_end']->format('d-m-Y') }}</td>
                        <td>{{ $sales['sales_last_ten_days']->count() }}</td>
                        <td>0 грн.</td>
                        <td>{{ $sales['total_sales_last_ten_days'] }} грн.</td>
                    </tr>
                    <tr>
                        <td>{{ $sales['second_ten_days']->format('d-m-Y') }}</td>
                        <td>{{ $sales['second_ten_days_end']->format('d-m-Y') }}</td>
                        <td>{{ $sales['sales_second_ten_days']->count() }}</td>
                        <td>0 грн.</td>
                        <td>{{ $sales['total_sales_second_ten_days'] }} грн.</td>
                    </tr>
                    <tr>
                        <td>{{ $sales['first_ten_days']->format('d-m-Y') }}</td>
                        <td>{{ $sales['first_ten_days_end']->format('d-m-Y') }}</td>
                        <td>{{ $sales['sales_first_ten_days']->count() }}</td>
                        <td>0 грн.</td>
                        <td>{{ $sales['total_sales_first_ten_days'] }} грн.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет продаж!
        </div>
    @endif
@endsection