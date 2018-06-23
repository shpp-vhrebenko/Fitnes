<header class="camotek-admin-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('admin') }}">GA</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Контент
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="{{ route('categories') }}">Категории</a>
                      <a class="dropdown-item" href="{{ route('admin_items') }}">Записи</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="{{ route('show_courses') }}">Курсы</a>
                      <a class="dropdown-item" href="{{ route('show_marathons') }}">Марафоны</a>                       
                    </div>
                </li>               
                <li class="nav-item"><a class="nav-link" href="{{ route('clients') }}">Клиенты</a></li>                
                <li class="nav-item"><a class="nav-link" href="{{ route('settings') }}">Настройки</a></li>
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Отчеты
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <h6 class="dropdown-header">Продажи</h6>
                        <a class="dropdown-item" href="#">Заказы</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Товары</h6>
                        <a class="dropdown-item" href="#">Просмотрено</a>
                        <a class="dropdown-item" href="#">Куплено</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Покупатели</h6>
                        <a class="dropdown-item" href="#">Активность покупателей</a>
                        <a class="dropdown-item" href="#">Заказы</a>
                    </div>
                </li> -->
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">Перейти на сайт</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Выйти</a></li>
            </ul>
        </div>
    </nav>
</header>