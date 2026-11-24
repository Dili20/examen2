<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Lista de Tareas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-light">
  @auth
  <nav class="navbar bg-white border-bottom">
    <div class="container">
      <span class="navbar-brand fw-bold">Lista de Tareas</span>
      <div class="d-flex align-items-center gap-3">
        <span>{{ auth()->user()->nombre }}</span>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="btn btn-sm btn-outline-secondary">Cerrar sesión</button>
        </form>
      </div>
    </div>
  </nav>
  @endauth

  <div class="container py-4">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>