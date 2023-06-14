<header class="p-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="/" class="nav-link px-2 text-secondary">Home</a></li>
                <li><a href="/works" class="nav-link px-2 text-white">Works</a></li>
                @if (Route::has('login'))
                @auth
                <li><a href="/works/create" class="nav-link px-2 text-white">Create</a></li>
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
                <a type="button" class="btn btn-outline-light me-2" href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                <a type="button" class="btn btn-outline-light me-2" href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                <a type="button" class="btn btn-warning" href="{{ route('register') }}">Register</a>
                @endif
                @endauth
            </div>
            @endif

        </div>
    </div>
</header>