@extends('layouts.app')
@section('content')
<a href="{{ route('listas.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Volver</a>
<h3 class="fw-bold">Nueva Lista</h3>

@if($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('listas.store') }}">
  @csrf
  <label class="fw-bold">Título *</label>
  <input name="titulo" class="form-control mb-3" placeholder="Ingrese el título de la lista" value="{{ old('titulo') }}" required>

  <label class="fw-bold">Ítems</label>
  <div class="border rounded p-2 mb-3 bg-white">
    <div class="input-group mb-2">
      <input id="nuevoItem" class="form-control" placeholder="Descripción del ítem">
      <button type="button" id="btnAgregar" class="btn btn-dark">Agregar</button>
    </div>
    <ul id="listaItems" class="list-group"></ul>
  </div>

  <button class="btn btn-dark">Guardar</button>
  <a href="{{ route('listas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</form>
@endsection

@push('scripts')
<script>
function agregarItem() {
  let d = $('#nuevoItem').val().trim();
  if (!d) return;
  let li = $('<li class="list-group-item d-flex justify-content-between align-items-center">');
  li.append($('<span>').text(d));
  li.append($('<input type="hidden" name="items[]">').val(d));
  li.append('<button type="button" class="btn btn-sm btn-outline-dark quitar"><i class="bi bi-trash"></i></button>');
  $('#listaItems').append(li);
  $('#nuevoItem').val('').focus();
}
$('#btnAgregar').click(agregarItem);
$('#nuevoItem').keypress(function (e) { if (e.which == 13) { e.preventDefault(); agregarItem(); } });
$(document).on('click', '.quitar', function () { $(this).closest('li').remove(); });
</script>
@endpush