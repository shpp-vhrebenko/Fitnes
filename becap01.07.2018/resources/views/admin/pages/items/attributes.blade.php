@extends('admin/layout/admin')

@section('content')
    <div class="title clearfix">
        <div class="text-left"><h1>{{ $title }}</h1></div>
        <div class="text-right"><a href="{{ route('admin_attributes_new') }}" class="btn btn-warning" style="color: #fff;">Создать</a></div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(isset($attributes) && $attributes->count() > 0)
        <div class="card">
            <div class="card-header">Атрибуты</div>
            <table class="table camotek-admin-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название атрибута</th>
                    <th scope="col">Фильтр</th>
                    <th scope="col">Тип сортировки</th>
                    <th scope="col">Для категорий</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Активация</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 0; ?>
                @foreach($attributes as $attribute)
                    <tr>
                        <th scope="row">{{ ++$index }}</th>
                        <td><a href="{{ route('show_attribute', $attribute->id) }}">{{ $attribute->locales[0]->name }}</a></td>
                        <td>{{ $attribute->is_filter ? 'Да' : 'Нет' }}</td>
                        <td>{{ $attribute->getTypeSort($attribute->sort_id) }}</td>
                        <td>
                            @if(isset($attribute->only_categories))
                                @foreach($attribute->only_categories as $key => $category)
                                    {{ $category }}@if($key < count($attribute->only_categories) - 1),@endif
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $attribute->created_at->format('d-m-Y') }}</td>
                        <td>{{ $attribute->is_active ? 'Да' : 'Нет' }}</td>
                        <td>
                            <ul class="camotek-form-links">
                                <li><a href="{{ route('edit_attribute', $attribute->id) }}" class="btn btn-primary">Изменить</a></li>
                                <li>
                                    <form class="delete" action="{{ route('destroy_attribute', $attribute->id) }}" method="POST">
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
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            Нет атрибутов!
        </div>
    @endif
@endsection

@section('footer-scripts')
    <script>
      $(document).ready(function () {
        $('.delete').on("submit", function () {
          return confirm('Вы действительно хотите удалить атрибут?');
        });
      });
    </script>
@endsection