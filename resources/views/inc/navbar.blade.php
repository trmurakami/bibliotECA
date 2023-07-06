<header class="p-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="/" class="nav-link px-2 text-secondary">Início</a></li>
                <li><a href="/works" class="nav-link px-2 text-white">Catálogo</a></li>
                <li><a href="/things" class="nav-link px-2 text-white">Autoridades</a></li>
                <li><a href="/graficos" class="nav-link px-2 text-white">Gráficos</a></li>
                <li><a href="/cutter" class="nav-link px-2 text-white">Cutter</a></li>
                <li><a href="/marcqa" class="nav-link px-2 text-white">MARC QA</a></li>
                @if (Route::has('login'))
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Ações
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/editor">Editor</a></li>
                        <li><a class="dropdown-item" href="/upload">Upload</a></li>
                        <li><a class="dropdown-item" href="/classificador/treinamento">Classificador -
                                Treinamento</a></li>
                    </ul>
                </li>
                @endauth
                @endif
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search" action="/works" method="get">
                <input type="search" class="form-control form-control-dark text-bg-dark" placeholder="Search..."
                    aria-label="Search" name="name">
            </form>

            @if (Route::has('login'))
            <div class="text-end">
                @auth
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <x-dropdown-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                        </li>
                        <li>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a type="button" class="btn btn-outline-light me-2" href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                <a type="button" class="btn btn-warning" href="{{ route('register') }}">Registrar</a>
                @endif
                @endauth
            </div>
            @endif

        </div>
    </div>
</header>