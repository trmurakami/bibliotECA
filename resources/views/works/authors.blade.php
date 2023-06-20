<!-- Display work details -->

<!-- Attach Person Form -->
<form action="{{ route('works.attachPerson', $work->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="person_ids">Authors:</label>
        <select name="person_ids[]" id="person_ids" multiple>
            @foreach ($people as $person)
            <option value="{{ $person->id }}">{{ $person->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit">Attach Authors</button>
</form>

<!-- Detach Person Form -->
@foreach ($work->authors as $author)
<form action="{{ route('works.detachPerson', [$work->id, $author->id]) }}" method="POST">
    @csrf
    @method('DELETE')
    <p>{{ $author->name }} <button type="submit">Detach</button></p>
</form>
@endforeach