<!-- resources/views/classificador/treinamento.blade.php -->

<form method="POST" action="{{ route('classificador.treinamento') }}">
    @csrf
    <div>
        <label for="strings">Digite as strings para treinamento (uma por linha):</label>
        <textarea name="strings" id="strings" rows="5" required></textarea>
    </div>
    <div>
        <label for="labels">Digite os rótulos correspondentes (uma por linha):</label>
        <textarea name="labels" id="labels" rows="5" required></textarea>
    </div>
    <div>
        <button type="submit">Treinar Modelo</button>
    </div>
</form>