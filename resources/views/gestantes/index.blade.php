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
                    <th class="py-2">Consultas</th>
                    <th class="py-2 text-right">Ações</th>
                </tr>
            </thead>
          <tbody>
    @forelse ($gestantes as $gestante)
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2">{{ $gestante->gestante_id }}</td>
            <td class="py-2">{{ $gestante->consultas_count }}</td>

            <td class="py-2 text-right">
                <div class="flex justify-end gap-3">

                    <!-- Ver Detalhes -->
                  <a href="{{ route('gestantes.show', $gestante) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm shadow-sm transition">
                        Ver detalhes
                    </a>

                    <!-- Editar -->
                    <a href="{{ route('gestantes.edit', $gestante) }}"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm">
                        Editar
                    </a>

                    <!-- Excluir -->
                    <form action="{{ route('gestantes.destroy', $gestante) }}"
                          method="POST"
                          onsubmit="return confirm('Tem certeza que deseja excluir esta gestante?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">
                            Excluir
                        </button>
                    </form>

                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="py-4 text-center text-gray-500">
                Nenhuma gestante encontrada
            </td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>
@endsection
