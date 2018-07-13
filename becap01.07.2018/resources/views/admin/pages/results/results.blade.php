@extends('admin/layout/admin')

@section('content')
    <h1>{{ $title }}</h1>

    <section class="camotek-admin-index-modules">        
        <div class="row">
            <div class="col-12 results-list">
                <div class="card">
                    <div class="card-header">
                        Результати клиента
                    </div>
                    <table class="table camotek-admin-table">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Изображение</th>
                            <th scope="col">Параметры</th>                           
                            <th scope="col">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>
                            @if(isset($result->image))
                                <a href="/uploads/results/{{ $result->image }}" class="item-preview" style="background-image: url('/uploads/results/{{ $result->image }}');" data-image="/uploads/results/{{ $result->image }}" data-toggle="lightbox">
                                    <img src="/uploads/results/{{ $result->image }}" alt="#" hidden>
                                </a>    
                            @endif
                            </td> 
                            <td>
                                <ul>
                                    <li>Вес: {{ $result->weight}}</li>
                                    <li>Рост: {{ $result->height }}</li>
                                    <li>Обьем груди: {{ $result->grud }}</li>
                                    <li>Обьем талии: {{ $result->taliya }}</li>
                                    <li>Обьем бедер: {{ $result->bedra }}</li>
                                </ul>
                            </td>                                                     
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

@section('footer-scripts')
    @parent
    <script>
      $(document).ready(function () {       
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
      });
    </script>
@endsection

@section('footer-assets')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
@endsection