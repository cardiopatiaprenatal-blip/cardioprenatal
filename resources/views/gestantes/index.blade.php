@extends('layouts.app')

@section('title', 'Gestantes')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">

        <div class="flex flex-row  justify-between items-center mb-6">

            <h2 class="text-lg font-semibold text-gray-700 mb-4">
                Gestantes cadastradas
            </h2>

            <a href="{{ route('gestantes.create') }}"
                class="inline-block mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Cadastrar nova gestante
            </a>

        </div>

        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="border-b text-left text-gray-500">
                    <th class="py-2">Gestante ID</th>
                    <th class="py-2">Data de Nascimento</th>
                    <th class="py-2">Consultas</th>
                    <th class="py-2 text-right">Ações</th>
                </tr>
            </thead>
          <tbody>
    @forelse ($gestantes as $gestante)
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2">{{ $gestante->gestante_id }}</td>
            <td class="py-2">{{ $gestante->data_nascimento ? \Carbon\Carbon::parse($gestante->data_nascimento)->format('d/m/Y') : 'N/A' }}</td>
            <td class="py-2">{{ $gestante->consultas_count }}</td>

            <td class="py-2 text-right">
                <div class="flex justify-end gap-3">

                    <!-- Ver Detalhes -->
                  <a href="{{ route('gestantes.show', $gestante) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm shadow-sm transition">
                        Ver detalhes
                    </a>

                    <!-- Editar -->
                <!-- Editar -->
                <a href="{{ route('gestantes.edit', $gestante) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm">
                    Editar
                </a>

                <!-- Excluir -->
                <button
                    onclick="abrirModal({{ $gestante->id }})"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm shadow-sm transition">
                    Excluir
                </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="py-4 text-center text-gray-500">
                Nenhuma gestante encontrada
            </td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>
 <div id="modalExcluir" class="hidden fixed inset-0 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-2xl p-6 w-96 border border-gray-200">

        <h2 class="text-lg font-semibold text-gray-800 mb-3">
            Confirmar exclusão
        </h2>

        <p class="text-gray-600 mb-6">
            Tem certeza que deseja excluir esta gestante?
        </p>

        <div class="flex justify-end gap-3">

            <button onclick="fecharModal()"
                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </button>

            <form id="formExcluir" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    Excluir
                </button>

            </form>

        </div>

    </div>

</div>
<script>
function abrirModal(id) {

    console.log("clicou", id);

    const modal = document.getElementById('modalExcluir')
    const form = document.getElementById('formExcluir')

    form.action = `/gestantes/${id}`

    modal.classList.remove('hidden')
}

function fecharModal() {
    document.getElementById('modalExcluir').classList.add('hidden')
}
</script>
@endsection
