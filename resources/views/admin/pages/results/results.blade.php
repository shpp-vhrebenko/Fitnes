@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>

    <section class="camotek-admin-index-modules">        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Результати клиента
                    </div>
                    <table class="table camotek-admin-table">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Вес</th>
                            <th scope="col">Рост</th>
                            <th scope="col">Обьем груди</th> 
                            <th scope="col">Обьем талии</th>
                            <th scope="col">Обьем бедер</th> 
                            <th scope="col">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>{{ $result->weight}}</td>
                            <td>{{ $result->height }}</td>
                            <td>{{ $result->grud }}</td> 
                            <td>{{ $result->taliya }}</td> 
                            <td>{{ $result->bedra }}</td>                          
                            <td>{{ $result->created_at }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $results->links() }} 
            </div>
        </div> 
            
    </section>

@endsection