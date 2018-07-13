@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('attribute_terms_new', $attribute->id) }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if(isset($terms) && $terms->count() > 0)
        <div class="card">
            <div class="card-header">Опции атрибута</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название опции</th>
                    <th scope="col">Изображение</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Просмотр</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 0; ?>
                @foreach($terms as $term)
                    <tr>
                        <th scope="row">{{ ++$index }}</th>
                        <td>{{ $term->locales[0]->name }}</td>
                        <td>@if(isset($term->image)) <img src="{{ $term->image }}" style="width: 50px;"/> @else Нет @endif </td>
                        <td>{{ $term->created_at->format('d-m-Y') }}</td>
                        <td>{{ $term->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_attribute_term', [$attribute->id, $term->id]) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_attribute_term', [$attribute->id, $term->id]) }}" method="POST">
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
            {{ $terms->links() }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет опций!
        </div>
    @endif
@endsection

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить опцию?');
        });
      });
    </script>
@endsection