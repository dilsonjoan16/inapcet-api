<?php

namespace App\Exports;

use App\Models\Departamento;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AuditoriaDepartamentosExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.AuditoriaDepartaments', [
            'departamentos' => Departamento::all()
        ]);
    }
}
