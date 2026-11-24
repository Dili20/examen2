<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $lista->titulo }}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 650px; margin: 40px auto; padding: 0 16px; color: #111827; }
        .meta { color: #4b5563; margin-bottom: 16px; }
        .barra-fondo { width: 100%; height: 22px; background: #e5e7eb; border-radius: 999px; overflow: hidden; margin: 10px 0 18px; }
        .barra-relleno { height: 100%; background: #16a34a; transition: width 0.25s ease; }
        .item-fila { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .completado { text-decoration: line-through; color: #6b7280; }
        .mensaje-exito { margin-bottom: 18px; padding: 12px 14px; background: #dcfce7; color: #166534; border-radius: 8px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $lista->titulo }}</h1>
    <p class="meta">Creada por: <strong>{{ $creador }}</strong></p>

    <div>
        <span id="texto-progreso">{{ $completados }} de {{ $total }} completados ({{ $porcentaje }}%)</span>
    </div>

    <div class="barra-fondo">
        <div id="barra-progreso" class="barra-relleno" style="width: {{ $porcentaje }}%;"></div>
    </div>

    <div id="mensaje-completado" class="mensaje-exito" style="display: {{ ($total > 0 && $completados === $total) ? 'block' : 'none' }};">
        ¡Lo lograste! Has completado todos los elementos de tu lista
    </div>

    <div>
        @forelse($items as $item)
            <div class="item-fila">
                <input
                    type="checkbox"
                    class="check-item"
                    data-item-id="{{ $item->id_item }}"
                    {{ $item->completado ? 'checked' : '' }}
                >
                <span id="texto-item-{{ $item->id_item }}" class="{{ $item->completado ? 'completado' : '' }}">
                    {{ $item->descripcion }}
                </span>
            </div>
        @empty
            <p>Esta lista aún no tiene ítems.</p>
        @endforelse
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const toggleUrlBase = "{{ url('/' . $lista->token . '/item') }}";

        document.querySelectorAll('.check-item').forEach((checkbox) => {
            checkbox.addEventListener('change', async function () {
                const itemId = this.dataset.itemId;
                const prevChecked = !this.checked;
                this.disabled = true;

                try {
                    const res = await fetch(`${toggleUrlBase}/${itemId}/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await res.json();
                    if (!res.ok || !data.ok) {
                        throw new Error(data.message || 'No se pudo actualizar');
                    }

                    const estaMarcado = Number(data.completado) === 1;
                    this.checked = estaMarcado;

                    const spanTexto = document.getElementById(`texto-item-${itemId}`);
                    if (spanTexto) {
                        spanTexto.classList.toggle('completado', estaMarcado);
                    }

                    document.getElementById('texto-progreso').textContent =
                        `${data.completados} de ${data.total} completados (${data.porcentaje}%)`;

                    document.getElementById('barra-progreso').style.width = `${data.porcentaje}%`;

                    document.getElementById('mensaje-completado').style.display =
                        data.todo_completado ? 'block' : 'none';
                } catch (err) {
                    this.checked = prevChecked;
                    alert('Error al guardar el cambio. Intenta de nuevo.');
                } finally {
                    this.disabled = false;
                }
            });
        });
    </script>
</body>
</html>