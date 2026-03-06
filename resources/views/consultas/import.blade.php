@extends('layouts.app')

@section('title', 'Importar Consultas')

@section('content')
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">
            Importar consultas (CSV)
        </h2>

        <form id="importForm" class="space-y-4">
            @csrf

            <input type="file" name="csv" accept=".csv"
                class="block w-full text-sm text-gray-600
                   file:mr-4 file:py-2 file:px-4
                   file:rounded file:border-0
                   file:text-sm file:font-semibold
                   file:bg-blue-50 file:text-blue-700
                   hover:file:bg-blue-100"
                required>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Importar CSV
            </button>
        </form>

        <div id="feedback" class="mt-4 text-sm hidden"></div>
    </div>

    <script>
        document.getElementById('importForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const feedbackDiv = document.getElementById('feedback');
            const submitButton = e.target.querySelector('button[type="submit"]');

            feedbackDiv.classList.add('hidden');
            submitButton.disabled = true;
            submitButton.innerText = 'Importando...';

            try {
                const response = await fetch("{{ route('consultas.import.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                    // Redireciona para a lista de gestantes em caso de sucesso.
                    window.location.href = "{{ route('gestantes.index') }}";
                } else {
                    const data = await response.json();
                    feedbackDiv.innerText = (data.message || 'Ocorreu um erro.') + (data.error ? ' Detalhes: ' + data.error : '');
                    feedbackDiv.className = 'mt-4 text-sm text-red-600';
                    feedbackDiv.classList.remove('hidden');
                    submitButton.disabled = false;
                    submitButton.innerText = 'Importar CSV';
                }

            } catch (error) {
                feedbackDiv.innerText = 'Erro de conexão ou ao processar o arquivo.';
                feedbackDiv.className = 'mt-4 text-sm text-red-600';
                feedbackDiv.classList.remove('hidden');
                submitButton.disabled = false;
                submitButton.innerText = 'Importar CSV';
            }
        });
    </script>
@endsection
