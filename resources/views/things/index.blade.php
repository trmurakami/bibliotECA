@extends('layouts.layout')

@section('title', 'BibliotECA - Autoridades')

@section('content')

<div class="row">
    <div class="col col-lg-12">
        <h2 class="mt-2 mb-2">Autoridades</h2>
        <form action="/things" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Pesquisar por nome" name="name"
                    value="{{ request()->name }}">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </div>
            <div class="d-flex mt-3 mb-3">
                <div class="mx-auto">
                    {!! $things->links() !!}
                </div>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($things as $thing)

                <li class="list-group-item">
                    <div class="d-flex bd-highlight">
                        <div class="w-100 bd-highlight">
                            @if ($thing->type == 'Person')
                            <span><img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAACCElEQVR4nO2Zz0obURTGf2OLpi/QuBI3Lly22gdw0624skEUQaHpSlqDfQHFVWteQoX2Abop9RF04cptEkFREFMoYmvKhTMg004wOndyz3h+8EGYG5L7fffm/jkBwzAMwzAMwzAMwzAMw/BNBEwAc8B7kXv9UtoKyzBQB1pAJ0WubQsoUyAiYA342cV4Um2gVoQZUQJ2ezCe1I58hkoiYPsB5mN91ToT1jIwH2sVZTwHLjMMoC2LqBrqGZqP9RklDADHHgJoaVkLJj2Yj+UOS8Ez5zGACgqoeQxAxW6w6jGADyig4jGAWRQw4TGAFyggAhoezDe1bIPIlTbrAD7xyI/CZZRRyzAAVzV6tNfhL5p++0lKUtS4r/ltzQWRmEgOR+0ejF/KtFc78v+jLFfaZhfjDVnt3SJaWCI50FRulcUr8qxQI24Yxj+U5LL0BqgCH0VVeebahigQEfAK2AAOgOs7bIHuPfvAupTXVC6Mg8ACcJjBSfAIWAGeoYBIaoLd/gC9r5qh1wRHgT0PxpP6AYwQGFPASQ7mY50BrwmEt8DvHM3Hct+53G/zS8CfPpiPdQO865f5GelAp89yAzCdt/kx4CIA87evz+N5mX8iB5VOYHJ9eppHAPMBmE3TYh4BfAvAaJq+5xHAaQBG03SeRwC/AjCaJtc372wCVwGYTepKbp2GYXBn/gIcmHnJ2f8WNwAAAABJRU5ErkJggg=="></span>
                            @elseif ($thing->type == 'Organization')
                            <span><img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAABbklEQVR4nO2bMU7EMBBFn2iQ0lFCB92eYC+TPlehgBOsBD2tQdqbUHEDKFmJLdbI0nZxbJyYMEv+l343TsZfM87MWAFBEAThR7gCHoFX4M0og28PwCWVcQN8AP5E+A5c1xTgxcCmSvlca/NnwN7Ahkr5dfR9MhoDmxnL4LsE+M0IcMBtgrHU2STsNxH7feYd7i8joM2s/YysWSXsVxH78IwUgg8SAEUASgF0BjD7IdgBFwnuImvWCft1xH6XeUfwQXUAKoRQJYhKYdQLoK8A6gVQM4S6QdQOo3kAM/QCbukTIW+cEgBFwHQ0BkLZbAq4pR+CbWatxuL0RdO9AMPQxQj9iNHNEIavxjqNxVl2HeCNUwKgCJiOxkAom02BbulfgTazVqUwfdFUCjMMlcKcWCncLj0F3IiJUKkApidCfgRLBTBdB3gJgCLAF4qwBZ4GuLWYAufAobJjc/Bw9L0K7g38EFXKu1qbFwRB4L/iGx1bMmko6xBWAAAAAElFTkSuQmCC"></span>
                            @endif
                            <a href="/works?author={{ $thing->name }}">{{ print_r($thing->name, true)}}</a>
                            @if ($thing->id_lattes13)
                            <a href="https://lattes.cnpq.br/{{ $thing->id_lattes13 }}" target="_blank">
                                <img src="data:image/png;base64,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"
                                    height="15" />
                            </a>
                            @endif
                        </div>
                        <div class="flex-shrink-1 bd-highlight"><span
                                class="badge text-bg-primary">{{ $thing->works_count }}</span>
                        </div>
                    </div>
                </li>

                @endforeach
            </ul>
            <div class="d-flex mt-3 mb-3">
                <div class="mx-auto">
                    {!! $things->links() !!}
                </div>
            </div>
    </div>
</div>

@endsection