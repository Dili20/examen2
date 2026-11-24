@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="fw-bold">Mis Listas</h3>
  <a href="{{ route('listas.create') }}" class="btn btn-dark"><i class="bi bi-plus"></i> Nueva Lista</a>
</div>

<table class="table bg-white border">
  <thead>
    <tr><th>#</th><th>Título</th><th>Token</th><th>Acciones</th></tr>
  </thead>
  <tbody>
    @forelse($listas as $l)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $l->titulo }}</td>
      <td><input class="form-control form-control-sm" value="{{ $l->token }}" readonly></td>
      <td>
        {{-- Botones del Estudiante B aquí --}}
        <a href="{{ route('listas.edit', $l) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-pencil-fill"></i></a>
        <form action="{{ route('listas.destroy', $l) }}" method="POST" class="d-inline"
              onsubmit="return confirm('¿Eliminar esta lista?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-dark"><i class="bi bi-trash-fill"></i></button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center text-muted">No tenés listas todavía</td></tr>
    @endforelse
  </tbody>
</table>
@endsection