<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerVisitantes extends Controller
{
    public function verLista($token)
    {
        $lista = DB::table('listas')
            ->join('usuarios', 'listas.id_usuario', '=', 'usuarios.id_usuario')
            ->select('listas.*', 'usuarios.nombre as creador')
            ->where('listas.token', $token)
            ->first();

        if (!$lista) {
            return response()->view('lista_no_encontrada', ['token' => $token], 404);
        }

        $items = DB::table('items')
            ->where('id_lista', $lista->id_lista)
            ->orderBy('id_item')
            ->get();

        $total = $items->count();
        $completados = $items->where('completado', 1)->count();
        $porcentaje = $total > 0 ? round(($completados / $total) * 100) : 0;

        return view('lista_publica', compact('lista', 'items', 'total', 'completados', 'porcentaje'));
    }

    public function toggleItem(Request $request, $token, $itemId)
    {
        $lista = DB::table('listas')
            ->where('token', $token)
            ->first();

        if (!$lista) {
            return response()->json(['ok' => false, 'message' => 'Lista no encontrada'], 404);
        }

        $item = DB::table('items')
            ->where('id_item', $itemId)
            ->where('id_lista', $lista->id_lista)
            ->first();

        if (!$item) {
            return response()->json(['ok' => false, 'message' => 'Ítem no válido para esta lista'], 404);
        }

        $nuevoEstado = $item->completado ? 0 : 1;

        DB::table('items')
            ->where('id_item', $itemId)
            ->where('id_lista', $lista->id_lista)
            ->update(['completado' => $nuevoEstado]);

        $items = DB::table('items')
            ->where('id_lista', $lista->id_lista)
            ->get();

        $total = $items->count();
        $completados = $items->where('completado', 1)->count();
        $porcentaje = $total > 0 ? round(($completados / $total) * 100) : 0;

        return response()->json([
            'ok' => true,
            'id_item' => $itemId,
            'completado' => $nuevoEstado,
            'completados' => $completados,
            'total' => $total,
            'porcentaje' => $porcentaje,
            'todo_completado' => ($total > 0 && $completados === $total),
        ]);
    }
}