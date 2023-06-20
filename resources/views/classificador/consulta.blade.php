<!-- resources/views/classificador/consulta.blade.php -->

<form method="POST" action="{{ route('classificador.consulta') }}">
    @csrf
    <div>
        <label for="string">Digite a string para consulta:</label>
        <input type="text" name="string" id="string" required>
    </div>
    <div>
        <button type="submit">Consultar</button>
    </div>
</form>