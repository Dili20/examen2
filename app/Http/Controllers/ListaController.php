<?php
namespace App\Http\Controllers;

use App\Models\Lista;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListaController extends Controller
{
    public function index()
    {
        $listas = Lista::where('id_usuario', auth()->id())->get();
        return view('listas.index', compact('listas'));
    }

    public function create()
    {
        return view('listas.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'titulo'  => 'required|max:200',
            'items'   => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($r) {
            $lista = Lista::create([
                'id_usuario' => auth()->id(),
                'titulo'     => $r->titulo,
                'token'      => $this->generarToken(),
            ]);
            foreach ($r->items as $d) {
                Item::create(['id_lista' => $lista->id_lista, 'descripcion' => $d]);
            }
        });

        return redirect()->route('listas.index');
    }

    public function edit(Lista $lista)
    {
        abort_if($lista->id_usuario != auth()->id(), 403);
        $lista->load('items');
        return view('listas.edit', compact('lista'));
    }

    public function update(Request $r, Lista $lista)
    {
        abort_if($lista->id_usuario != auth()->id(), 403);
        $r->validate(['titulo' => 'required|max:200']);

        DB::transaction(function () use ($r, $lista) {
            $lista->update(['titulo' => $r->titulo]);

            $existentes = $r->input('existentes', []);
            $lista->items()->whereNotIn('id_item', array_keys($existentes))->delete();

            foreach ($existentes as $id => $d) {
                $lista->items()->where('id_item', $id)->update(['descripcion' => $d]);
            }
            foreach ($r->input('nuevos', []) as $d) {
                $lista->items()->create(['descripcion' => $d]);
            }
        });

        return redirect()->route('listas.index');
    }

    public function destroy(Lista $lista)
    {
        abort_if($lista->id_usuario != auth()->id(), 403);
        $lista->delete();
        return redirect()->route('listas.index');
    }

    private function generarToken()
    {
        do {
            $t = Str::random(20);
        } while (Lista::where('token', $t)->exists());
        return $t;
    }
}