@extends('principal')

@section('imagen-encabezado')
<div style="background-image: url('{{ asset('images/reporte_eventos.jpg') }}'); height: 100px; background-size: cover; background-position: center;">
</div>
@endsection

@section('contenido')
<div class="container mt-5">
    <h1 class="text-center mb-4">Reporte de Eventos</h1>
    <p class="text-center"><strong>Mes:</strong> {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->translatedFormat('F Y') }}</p>
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first('mensaje') }}
        </div>
    @endif

    @if (count($reportes) > 0)
        <div class="calendar mt-4">
            @php
                $carbonMes = \Carbon\Carbon::createFromFormat('Y-m', $mes);
                $inicioMes = $carbonMes->startOfMonth();
                $finMes = $carbonMes->endOfMonth();
                $diasEnMes = $carbonMes->daysInMonth;
                $primerDiaSemana = $inicioMes->dayOfWeek; // 0 para domingo, 1 para lunes...
            @endphp

            <!-- Generar encabezados de la semana -->
            <div class="calendar-header table-dark text-white text-center py-2">
                <div class="day-header">Domingo</div>
                <div class="day-header">Lunes</div>
                <div class="day-header">Martes</div>
                <div class="day-header">Miércoles</div>
                <div class="day-header">Jueves</div>
                <div class="day-header">Viernes</div>
                <div class="day-header">Sábado</div>
            </div>


            <div class="calendar-grid">
                <!-- Espacios vacíos antes del inicio del mes -->
                @for ($i = 0; $i < $primerDiaSemana; $i++)
                    <div class="calendar-cell empty"></div>
                @endfor

                <!-- Días del mes con eventos -->
                @for ($dia = 1; $dia <= $diasEnMes; $dia++)
                    @php
                        $fechaDia = $carbonMes->copy()->day($dia)->format('Y-m-d');
                        $eventosDia = collect($reportes)->filter(function($evento) use ($fechaDia) {
                            return \Carbon\Carbon::parse($evento->fechaEvent)->format('Y-m-d') === $fechaDia;
                        });
                    @endphp

                    <div class="calendar-cell">
                        <div class="date-header">{{ $dia }}</div>
                        @if ($eventosDia->isNotEmpty())
                            <ul class="event-list">
                                @foreach ($eventosDia as $evento)
                                    <li>ID: {{ $evento->idCoEvent }} - {{ $evento->tipoDeEvento }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="no-event">Sin eventos</p>
                        @endif
                    </div>
                @endfor

                <!-- Espacios vacíos después del último día del mes -->
                @php
                    $diasRestantes = (7 - ($primerDiaSemana + $diasEnMes) % 7) % 7;
                @endphp
                @for ($i = 0; $i < $diasRestantes; $i++)
                    <div class="calendar-cell empty"></div>
                @endfor
            </div>
        </div>
    @else
        <p class="text-center text-muted mt-4">No hay eventos registrados para este mes.</p>
    @endif
    

    <div class="text-center mt-4">
        <!-- Botón para imprimir la pantalla -->
        <button onclick="window.print()" class="btn btn-secondary rounded-pill px-3 me-2">Imprimir</button>
        <!-- Botón para volver a la vista anterior -->
        <a href="{{ route('reportareventos') }}" class="btn btn-secondary rounded-pill px-3 me-2">Cancelar</a>
    </div>

</div>

<style>
    body {
            background-color: #A6A6A6; /* Fondo gris */
        }
    .calendar {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        font-weight: bold;
        text-align: center;
        padding: 10px;
        background-color: #343a40; /* Dark header color */
        color: #ffffff; /* White text */
        border-radius: 5px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }
    .calendar-cell {
        border: 1px solid #ccc;
        background-color: #fff;
        padding: 10px;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        position: relative;
    }
    .calendar-cell.empty {
        background-color: #f5f5f5;
    }
    .date-header {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 16px;
    }
    .event-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
        font-size: 14px;
    }
    .event-list li {
        margin-bottom: 5px;
        background-color: #e0f7fa;
        padding: 5px;
        border-radius: 3px;
        word-wrap: break-word;
    }
    .no-event {
        color: #999;
        font-size: 14px;
    }
</style>
@endsection