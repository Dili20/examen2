@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-center mt-5">
  <div class="card p-4 text-center" style="width: 320px">
    <h4 class="fw-bold">Lista de Tareas</h4>
    <p class="text-muted small">Accede con tu cuenta de Google</p>
    <a href="{{ route('google.login') }}" class="btn btn-outline-dark">
      <i class="bi bi-google me-2"></i>Iniciar sesión con Google
    </a>
  </div>
</div>
@endsection