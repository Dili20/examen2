<?php

namespace App\Http\Controllers;

use App\Models\Lista;
use Illuminate\Http\Request;

class ControllerVisitantes extends Controller
{
    public function verLista($token)
    {
        $lista = Lista::with(['usuario', 'items'])->where('token', $token)->first();

        if (!$lista) {
            return response()->view('lista_no_encontrada', ['token' => $token], 404);
        }

        $items       = $lista->items;
        $total       = $items->count();
        $completados = $items->where('completado', 1)->count();
        $porcentaje  = $total > 0 ? round(($completados / $total) * 100) : 0;
        $creador     = $lista->usuario->nombre ?? $lista->usuario->name ?? 'Desconocido';

        return view('lista_publica', compact('lista', 'items', 'total', 'completados', 'porcentaje', 'creador'));
    }

    public function toggleItem(Request $request, $token, $item)
    {
        $lista = Lista::where('token', $token)->first();

        if (!$lista) {
            return response()->json(['ok' => false, 'message' => 'Lista no encontrada'], 404);
        }

        // Valida que el ítem pertenezca a esa lista
        $itemModel = $lista->items()->where('id_item', $item)->first();

        if (!$itemModel) {
            return response()->json(['ok' => false, 'message' => 'Ítem no pertenece a la lista'], 404);
        }

        $itemModel->completado = $itemModel->completado ? 0 : 1;
        $itemModel->save();

        $total       = $lista->items()->count();
        $completados = $lista->items()->where('completado', 1)->count();
        $porcentaje  = $total > 0 ? round(($completados / $total) * 100) : 0;

        return response()->json([
            'ok'              => true,
            'completado'      => (int) $itemModel->completado,
            'completados'     => $completados,
            'total'           => $total,
            'porcentaje'      => $porcentaje,
            'todo_completado' => $total > 0 && $completados === $total,
        ]);
    }
}