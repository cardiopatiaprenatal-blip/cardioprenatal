<form action="{{ route('consultas.update', $consulta->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Data da consulta</label>
    <input type="date" name="data_consulta" value="{{ $consulta->data_consulta }}">

    <label>Idade gestacional</label>
    <input type="number" name="idade_gestacional" value="{{ $consulta->idade_gestacional }}">

    <label>Peso</label>
    <input type="number" step="0.01" name="peso" value="{{ $consulta->peso }}">

    <button type="submit">Atualizar</button>
</form>