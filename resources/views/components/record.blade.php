<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4 d-flex align-items-center justify-content-center" style="max-width: 100px;">
            @if ($work->cover)
            <a href="{{ route('works.show',$work->id) }}">
                <img src="{{ asset('storage/cover/'.$work->cover) }}" class="img-fluid rounded-start" alt="Cover">
            </a>
            @else
            @if ($work->type == 'Book')
            <a href="{{ route('works.show',$work->id) }}">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABJklEQVR4nO2aMUoDURCGvyIQJBEULAKeIDfwCLESvIc2Fp7BO+UABlLYWVgIL0dQG5tfFqfYRpCXzL6dMB9M+R7v2/n3FTsLSTymwB3wDHwCalxVXAIvIzi89hGZ9iRegRvglHaoVuS+J3FGe1QrsrGFXSeILPJhC1vG6SAie90SUURWwM7pViq2/yAixfmKLUOJeMZOf+ydIsqOBIpWOZaXfeUoU4a8flugFCE74oIyWmS0XFBGi4yWC8pokdFyQRktMlouKKNFRssFZbT4fQpz4BHYAl/OX0/0j6pu5fsIDq9DiMgGodfABQGR1RqYEBhZLQnOt4l0093QvJnIFcF5MpFu1n5CYM57c8Ju1n4LLAjKshexsVQ1M+DBfiAI+1NNwsD8ADn+3S5IoeVoAAAAAElFTkSuQmCC"
                    class="mx-auto d-block" alt="Cover">
            </a>
            @elseif ($work->type == 'Article')
            <a href="{{ route('works.show',$work->id) }}">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADb0lEQVR4nO2YWYhPURzHP8YyhAf7FmLsCuWJMVPKG8lelFK8CA+2DPHiQYkR8YLIll1SyguJaax5YLKE7NRkrKPsjk59b/26+fOf/9x7/3cmvzrde5Z7zvnc33J+98J/Sbe4GMtdoGtjAHFJwjiVuOZNDMbFDFJlrp1iWCcxEL/5W0loxsUMQlIwLgGQRGBcQiCxw7gEQWKFcQmDxAbj8gASC4zLE0jkMC6Bkz3bUhXFglFLrrlZ6kAS34dLEciPxgBSAYww9X7AOuAyUAmMbCggTXTtAxyQdqzvHGsoIC2BtcAX7ecbsAf4qPq2NIC0AYYCHTL0FwP3QxqYCkwEfgFfgf71BcklhO6TqRQA5SEz+Qw8lE8cBPabfn84HtH9C+Ct7suyeVtRg5wFWujZLWbz94DaDM/8kFk1V7lg+k7qhdQbJFdZZiBKTHtrYKHRwlNgjOnvLminiOXHky+QGcBPlemmvYnMJIA4HfKbXsZXngHt67Jo1CAl0oKfc4lpbwUcVrsHXG1CLgoGz8x+vJPPIk8gg4E3mm+zae8MXFG7D6cTQs+NNY59EVilex+KS6MC+Zdzz9c4n4I/VtsJ46D9FaWC8a+ATcak5im8BoeeP0/QGKcXMyhukCqZh1/8qtoqZUYorajO8Gy1MTUHrA9FJ39/XH2PgC71BclGFmmOB0BHtY0G3huf8NddMpVzIag15mA8D0xT3b+QSxpz7V8RLAqQHSEzG2VSi6PAdt0vVb/X4lzjFx+BnUpLnJJF+xUZmOYpoGmcIMs1xw1gNvBO9YNaeIrqH0IZbjf5hY1UG4BmofkHADUm5yqIC6Sd7NiaizenYeofaMzMO3vv0POT9M0++S9rFJuw/hyYGXXU8oldELWCzX7S9b7sP9jAd13v1PWwk0w2L8xrO1KQ8Rq3RPWXQF/gSWjcbqAncFP1ChNq6ypDTGSM1LQKjQ1vBa6beb1WxpmxPcwJ7sNrVglhNhIFyPA/aOq1EkcPGZahJmJtJSUgbRX7H2lzPsVYoI+pv0mp8Z2VpADEm8riHE3EZ8bBYVmeQXt5y37rKnNMNHsobRY1RBD0YXU75GO1Skv2AisU5ovSDoJOc29qhxTCM4X7srSDFP4hWyhWmr8ROCOtBQdwakFq9LMiZ0kLiKvvPlzKSs7SaED+C3mW33gKVOyph/K/AAAAAElFTkSuQmCC"
                    class="mx-auto d-block" alt="Cover">
            </a>
            @endif

            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('works.show',$work->id) }}">{{ $work->name }}
                        ({{ $work->datePublished }})</a></h5>
                <p class="card-text"><small class="text-body-secondary">{{ $work->type }}</small></p>
                @php
                if (isset($work->author)) {
                $namesArray = array_map(function ($author) {
                return $author['name'];
                }, $work->author);
                $string = implode(', ', $namesArray);
                }
                @endphp
                @if (isset($namesArray))
                <p class="card-text"><small class="text-body-secondary">Autores:
                        {{ implode(', ', $namesArray) }}</small>
                </p>
                @endif
                @if (isset($work->isPartOf_name))
                <p class="card-text">
                    <small class="text-body-secondary">Publicado em: {{ $work->isPartOf_name }}
                    </small>
                </p>
                @endif
                @if (isset($work->conferenceName))
                <p class="card-text">
                    <small class="text-body-secondary">Apresentado no evento: {{ $work->conferenceName }}
                    </small>
                    @endif
                <p class="card-text">{{ $work->description }}</p>
            </div>
        </div>
    </div>
</div>