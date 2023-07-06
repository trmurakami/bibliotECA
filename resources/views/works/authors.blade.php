<!-- Display work details -->

<!-- Attach Thing Form -->
<form action="{{ route('works.attachThing', $work->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="thing_ids">Authors:</label>
        <select name="thing_ids[]" id="thing_ids" multiple>
            @foreach ($things as $thing)
            <option value="{{ $thing->id }}">{{ $thing->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit">Attach Authors</button>
</form>

<!-- Detach Thing Form -->
@foreach ($work->authors as $author)
<form action="{{ route('works.detachThing', [$work->id, $author->id]) }}" method="POST">
    @csrf
    @method('DELETE')
    <p>{{ $author->name }} <button type="submit">Detach</button></p>
</form>
@endforeach