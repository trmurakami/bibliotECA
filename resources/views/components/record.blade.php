<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4" style="max-width: 200px;">
            @if ($work->cover)
            <a href="{{ route('works.show',$work->id) }}">
                <img src="{{ asset('storage/cover/'.$work->cover) }}" class="img-fluid rounded-start" alt="Cover">
            </a>
            @else
            @if ($work->type == 'Book')
            <a href="{{ route('works.show',$work->id) }}">
                <img src="{{ asset('storage/cover/default.png') }}" class="img-fluid rounded-start" alt="Cover">
            </a>
            @elseif ($work->type == 'Article')
            <a href="{{ route('works.show',$work->id) }}">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAb1BMVEX///8AAABhYWGlpaXb29v09PR3d3fv7+8wMDAPDw/i4uL6+vrq6uqOjo7Nzc2ioqJdXV04ODhDQ0NmZmZra2snJydfX18WFhZTU1PKysopKSmGhoaTk5OKiooICAgREREeHh5NTU09PT3CwsK5ubmldu12AAADeUlEQVR4nO3d21biQBCF4S4BgyRy8DQiePb9n3ECXmJlJlTtql4z+7+3m49u4lptJKUwxhhjjDHGGGOMMcbymk4uL3xa77ItP7XciGN32ZzT9ofXdeOygrebGom7/kVNvAbbV7iKW5GXxm206+NGvXUbz6N7kc5vtF64rYy4FHl2HK4XNou6iNeuS/g9XF3ECxHP4b7fsKqIEGFVRIywdPUQQcKKiChhPUSY8Ei88Bz7zHDCWohA4ZH44Dn6WSGFpXupgAgVVrGKWGHprkTWnhOMDywsjWQT0cLSZK8iXFia11wiXlia/or66DnJuAKEZfYrkxghLE0mMURYZm95xBhh5kYdEM62S6WterSjHvvkraIunA0d3U+VH9IPttJWURd2vsLjKr47vOKxDezS5WqitFpqPzN0OJlEDLrSHDsQbzxn+6sihaW9TyCGClNWMVaYsYrBwgRitLC0T8HEcGH4KsYLo1cxQXgkfnjOOliGMJaYIiztXGTjOe9AOcJIYpIwkJgljCOmCUu7iSHmCUs5rGLrOfuPZQpjVtFfOOYmuXeROXoV/YXrETdrrgVPdBY+n3HLLfiuYmfh9AzhpecLOG1AuFL328PA296Oq0kULobe90+v+WeZa7iRKyW5cbs6pApDotAehegotEchOgrtUYiOQnsUoqPQHoXoKLRHIToK7f3fwqlf6hyZwvaM42s97fgxU9i4CrW/SKXuUv0O2tGt1G3KK409CtFRaI9CdBTaoxAdhfYoREehPQrRUWiPQnQU2qMQXapw6njW9qVN8u+cl6rfQ8Azb3MDu7R1/LuFek8xrzT2KERHoT0K0VFoj0J0FNqjEB2F9ihER6E9CtFRaI9CdKnCO/X/8Ucnc22STOHgdyqMbqHMkrqG+xHfw/KH1nttEn4O7VGIjkJ7FKKj0B6F6Ci0RyE6Cu1RiI5CexSio9AehehyhQu/1DlS7y99cTwufdXmzxQOPpVsdFV+p8LW8T5v9TFmvNLYoxAdhfYoREehPQrRUWiPQnQU2qMQHYX2KETXwZ9otdOfBB/Sp8gKPgN4lwz3CH+HD/+Trh4T4fsSeULPsZfEfdrvIL/Hf6l99LPsOvyzJE9qp3cS8hk5PH64bx7d97S3eGDfpevp9qgmIcD+l9L+KYP3Pgn9bDThReoYY4wxxhhjjDHGGGMn/QY08jnM3pjTswAAAABJRU5ErkJggg=="
                    class="img-fluid rounded-start" alt="Cover">
            </a>
            @endif

            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('works.show',$work->id) }}">{{ $work->name }}
                        ({{ $work->datePublished }})</a></h5>
                <p class="card-text"><small class="text-body-secondary">{{ $work->type }}</small></p>
                <p class="card-text">{{ $work->description }}</p>
            </div>
        </div>
    </div>
</div>