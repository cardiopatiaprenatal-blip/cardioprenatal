<?php

namespace App\Http\Controllers;

use App\Models\Gestante;
use Illuminate\Http\Request;

class GestanteController extends Controller
{
    public function index()
    {
        $gestantes = Gestante::withCount('consultas')->orderBy('gestante_id')->get();

        return view('gestantes.index', compact('gestantes'));
    }

    public function create()
    {
        return view('gestantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gestante_id' => 'required',
            'data_nascimento' => 'required|date',
        ]);

        // Usar request->all() é seguro aqui por causa da propriedade $fillable no Model Gestante
        Gestante::create($request->all());

        return redirect()->route('gestantes.index')->with('success', 'Gestante cadastrada com sucesso!');
    }

    public function show(Gestante $gestante)
    {
        $gestante->load(['consultas' => function ($query) {
            $query->orderBy('data_consulta');
        }]);

        return view('gestantes.show', compact('gestante'));
    }

    public function edit($id)
    {
        $gestante = Gestante::findOrFail($id);
        return view('gestantes.edit', compact('gestante'));

    }

    public function update(Request $request, $id)
    {
         $gestante = Gestante::findOrFail($id);

            $request->validate([
                'gestante_id' => 'required',
                'data_nascimento' => 'required|date',
            ]);

            $gestante->update($request->all());

            return redirect()->route('gestantes.index')
                ->with('success', 'Gestante atualizada com sucesso!');
    }

    public function destroy($id)
    {
        //
         $gestante = Gestante::findOrFail($id);

         $gestante->delete();

          return redirect()->route('gestantes.index')
        ->with('success', 'Gestante excluída com sucesso!');
    }
}
