<?php

namespace App\Exports;

use App\Models\Proyecto;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AuditoriaProyectosExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.AuditoriaProyects', [
            'proyectos' => Proyecto::all()
        ]);
    }
}
