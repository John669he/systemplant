<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\Models\agendarcitas;
use App\Models\clientes;
use App\Models\contratosevent;
use App\Models\cotizareventos;
use App\Models\detallespagoss;
use App\Models\detallesps;
use App\Models\encuestas;
use App\Models\eventos;
use App\Models\municipios;
use App\Models\productosservicios;
use App\Models\provedoes;
use App\Models\tipospagos;

use Session;
use Carbon\Carbon;


class menucontroller extends Controller
{
    public function principal()
    {
        return view('principal');
    }
    public function inicio()
    {
        return view('inicio');
    }
    public function tipoevento()
    {
        return view('tipoevento');
    }


    //Funciones para función Catalogar Clientes
    public function clientes()
    {
        $municipios = Municipios::orderBy('nombre', 'asc')->get();
        return view('clientes')->with('municipios', $municipios);
    }

    public function consultar()
    {
        $clientes = Clientes::with('municipio')->get(); // Incluye la relación del municipio
        return view('clientes.consultar', compact('clientes'));
    }
    public function editar($idCli)
    {
        $clientes = Clientes::find($idCli); // Encontrar el cliente por ID
        $municipios = Municipios::orderBy('nombre', 'asc')->get(); // Obtener la lista de municipios para el select
        return view('clientes.editar', compact('clientes', 'municipios'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:2|max:50',
            'apP' => 'required|string|min:2|max:50',
            'apM' => 'required|string|min:2|max:50',
            'calle' => 'required|string|min:5|max:100',
            'idMun' => 'required|exists:municipios,idMun',
            'correo' => 'required|email|max:100',
            'tel' => 'required|digits:10',
        ], [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.min' => 'El Nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El Nombre no debe exceder los 50 caracteres.',
            'correo.email' => 'El formato del Correo no es válido.',
            'tel.digits' => 'El Teléfono debe tener exactamente 10 dígitos.',
        ]);

        clientes::create($request->all());

        return redirect()->back()->with('success', 'Cliente registrado con éxito.');
    }
    public function actualizar(Request $request, $idCli)
    {
        $request->validate([
            'nombre' => 'required|string|max:20',
            'apP' => 'required|string|max:20',
            'apM' => 'required|string|max:20',
            'correo' => 'required|email|max:50',
            'tel' => 'required|string|max:10',
            'calle' => 'required|string|max:30',
            'idMun' => 'required|string',
        ]);

        $clientes = Clientes::find($idCli);
        $clientes->nombre = $request->nombre;
        $clientes->apP = $request->apP;
        $clientes->apM = $request->apM;
        $clientes->correo = $request->correo;
        $clientes->tel = $request->tel;
        $clientes->calle = $request->calle;
        $clientes->idMun = $request->idMun;
        $clientes->save();

        return redirect()->route('clientes.consultar')->with('success', 'Cliente actualizado correctamente');
    }

    public function eliminacliente($idCli)
    {
        try {
            // Intenta eliminar el cliente
            $deleted = Clientes::where('idCli', $idCli)->delete();

            if ($deleted) {
                // Mensaje de éxito si se eliminó correctamente
                return redirect()->route('clientes.consultar')->with('success', "El cliente con ID $idCli ha sido eliminado correctamente.");
            } else {
                // Mensaje si no se encuentra el cliente
                return redirect()->route('clientes.consultar')->with('error', "El cliente con ID $idCli no pudo ser encontrado.");
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura el error de restricción de clave foránea y muestra un mensaje
            return redirect()->route('clientes.consultar')->with('error', "No se puede eliminar el cliente con ID $idCli porque está relacionado con otras tablas");
        }
    }


    public function proveedores()
    {
        return view('proveedores');
    }
    public function contratarevento()
    {
        return view('contratarevento');
    }
    public function cotizarevento()
    {
        return view('cotizarevento');
    }


    //Funciones para función Agendar Cita
    public function agendarcita()
    {
        // Obtiene los clientes para mostrarlos en el select
        $clientes = DB::table('clientes')->get();

        return view('agendarcita', compact('clientes'));
        //$clientes = DB::table('clientes')->select('idCli', 'nombre', 'apP','apM')->get();
        //return view('agendarcita', ['clientes' => $clientes]);
    }
    // Función para cargar los horarios disponibles
    public function obtenerHorariosDisponibles(Request $request)
    {
        $fecha = $request->input('fecha');

        // Lista de horarios predeterminados
        $horarios = [
            '08:00', '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00', '17:00'
        ];

        // Obtener los horarios ocupados de la base de datos
        $horariosOcupados = Cita::where('fecha', $fecha)->pluck('hora')->toArray();

        // Filtrar horarios disponibles
        $horariosDisponibles = array_diff($horarios, $horariosOcupados);

        return response()->json($horariosDisponibles);
    }
    // Función para guardar la cita
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'idCli' => 'required|exists:clientes,idCli',
        ]);

        // Crear la cita
        Cita::create([
            'fecha' => $request->input('fecha'),
            'hora' => $request->input('hora'),
            'idCli' => $request->input('idCli'),
        ]);

        return redirect()->back()->with('success', 'Cita agendada con éxito.');
    }


     
     


    public function controlpagos()
    {
        return view('controlpagos');
    }
    public function experiencias()
    {
        return view('experiencias');
    }


    //Funciones para función Reportar Ingresos
    public function reportaringresos()
    {
        return view('reportaringresos'); // Muestra el formulario de fecha
    }

    public function generarReporte(Request $request)
    {
        // Validar las fechas de inicio y fin
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Realizar la consulta para obtener las fechas y los montos totales
        $reportes = DB::table('cotizareventos AS CE')
            ->join('detallespagoss AS DP', 'CE.idCoEvent', '=', 'DP.idCoEvent')
            ->select(
                DB::raw('DATE(CE.fechaEvent) AS Fecha_Evento'), // Asegura que solo se utilice la parte de la fecha
                DB::raw('SUM(DP.monto) AS Total_Monto') // Agrupa y suma los montos
            )
            ->whereBetween(DB::raw('DATE(CE.fechaEvent)'), [$startDate, $endDate]) // Filtra por el rango de fechas
            ->groupBy(DB::raw('DATE(CE.fechaEvent)')) // Agrupa por fecha
            ->orderBy(DB::raw('DATE(CE.fechaEvent)'))
            ->get();

        // TOTAL
        $totalMonto = $reportes->sum('Total_Monto');

        // Pasar los datos a la vista
        return view('reporteresultado', compact('reportes', 'startDate', 'endDate', 'totalMonto'));
    }




    public function reportareventos()
    {
        return view('reportareventos');
    }

    public function generarReporteEventos(Request $request)
    {
        // Validar que el mes esté en formato correcto
        $this->validate($request, [
            'mes' => 'required|date_format:Y-m',
        ]);

        $mes = $request->input('mes'); // Obtener el mes del formulario (formato YYYY-MM)

        // Consultar los eventos que coincidan con el mes
        $reportes = \DB::select("SELECT 
                c.idCoEvent, co.fechaEvent, c.fechaContra, e.nombre AS tipoDeEvento, co.costo
            FROM contratosevent AS c
            INNER JOIN cotizareventos AS co ON co.idCoEvent = c.idCoEvent
            INNER JOIN eventos AS e ON e.idCatEvent = co.idCatEvent
            WHERE co.fechaEvent LIKE ?", ["$mes%"]);

        // Si no hay eventos, mostrar mensaje en la vista
        if (empty($reportes)) {
            return view('reporte-eventos', [
                'reportes' => [],
                'mes' => $mes,
            ])->withErrors(['mensaje' => 'No hay eventos registrados para este mes.']);
        }

        // Si hay eventos, enviarlos a la vista
        return view('reporte-eventos', compact('reportes', 'mes'));
    }

    public function altamenu()
        {
            return ('altamenu');
        }
        public function guardamenu(Request $request)
        {
            echo $request->nombre;
        }

        
}

