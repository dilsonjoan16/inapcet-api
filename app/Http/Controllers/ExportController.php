<?php

namespace App\Http\Controllers;

use App\Exports\AuditoriaDepartamentosExport;
use App\Exports\AuditoriaDocumentosExport;
use App\Exports\AuditoriaProyectosExport;
use App\Exports\AuditoriaUserExport;
use App\Exports\ProyectosExport;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class ExportController extends Controller
{
    public function exportDepartaments()
    {
        return Excel::download(new AuditoriaDepartamentosExport, 'departamentos-INAPCET.xlsx');
    }

    public function exportUsers()
    {
        return Excel::download(new AuditoriaUserExport, 'usuarios-INAPCET.xlsx');
    }

    public function exportDocuments()
    {
        return Excel::download(new AuditoriaDocumentosExport, 'archivos-INAPCET.xlsx');
    }

    public function exportProyects()
    {
        return Excel::download(new AuditoriaProyectosExport, 'proyectos-INAPCET.xlsx');
    }

    public function exportDataProyect($id)
    {
        // return (new ProyectosExport($id))->download('proyecto.pdf', \Maatwebsite\Excel\Excel::DOMPDF);

        set_time_limit(300);

        $proyecto = Proyecto::find($id);

        $pdf = PDF::loadView('exports.Proyecto', compact('proyecto'));
        return $pdf->download('proyecto.pdf');

    }
}
