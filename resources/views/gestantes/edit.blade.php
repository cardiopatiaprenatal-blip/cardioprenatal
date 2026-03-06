@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto mt-10">

    <div class="bg-white shadow-lg rounded-xl p-8">

        <h2 class="text-2xl font-semibold text-gray-700 mb-6">
            Editar Gestante
        </h2>

        <form action="{{ route('gestantes.update', $gestante->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        ID da Gestante
                    </label>
                    <input
                        type="text"
                        name="gestante_id"
                        value="{{ $gestante->gestante_id }}"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Data de Nascimento
                    </label>
                    <input
                        type="date"
                        name="data_nascimento"
                        value="{{ $gestante->data_nascimento }}"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500"
                    >
                </div>

            </div>

            <div class="mt-8 flex justify-end">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                    Atualizar
                </button>
            </div>

        </form>

    </div>

</div>

@endsection