@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($activities) && $activities->count() > 0)
        <div class="card">
            <div class="card-header">Аквтивность клиентов</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">Действие</th>
                    <th scope="col">IP</th>
                    <th scope="col">Дата создания</th>
                </tr>
                </thead>
                <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td>@if($activity->user_id){{ $activity->client->name }}@elseгость@endif - @if($activity->order_id)<a href="{{ route('show_order', $activity->order_id) }}">{{ $activity->getActionById($activity->action_id) }}</a>@else{{ $activity->getActionById($activity->action_id) }}@endif</td>
                        <td>{{ $activity->ip }}</td>
                        <td>{{ $activity->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $activities->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет истории активности!
        </div>
    @endif
@endsection