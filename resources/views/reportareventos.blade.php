<!-- Vista para pedir el mes y Reportar evento --> 
@extends('principal')

@section('imagen-encabezado')
<div style="background-image: url('{{ asset('images/reporte_eventos.jpg') }}'); height: 100px; background-size: cover; background-position: center;">
</div>
@endsection

@section('contenido')

<style>
        body {
            background-color: #A6A6A6; /* Fondo gris */
        }
</style>

    <div style="text-align: center; margin-bottom: 20px;">
        <h1 style="font-family: 'Times New Roman',serif;font-weight: bold; font-size:60px"
            class = "text-center mb-4" >Reporte de Eventos</h1>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; height: 400px;">
        <!-- Imagen del lado izquierdo -->
        <div style="background-image: url('{{ asset('images/calendario.png') }}'); 
                    background-size: contain; 
                    background-position: center; 
                    background-repeat: no-repeat; 
                    height: 95%; 
                    width: 50%;">
        </div>

        <div style="display: flex; flex-direction: column; justify-content: center; align-items: flex-start; width: 50%; padding: 20px;">
            <form action="{{ route('generarReporteEventos') }}" method="POST" style="width: 100%;">
                @csrf
                <div class="mb-3">
                    <label for="mes" class="form-label">Selecciona el mes:</label>
                    <input type="month" id="mes" name="mes" required style="padding: 5px; width: 100%;">
                </div>
                <div class="center">
                <button type="submit" class="btn btn-secondary rounded-pill px-3 me-2">Mostrar reporte</button>
                </div>
            </form>
        </div>
    </div>
@stop