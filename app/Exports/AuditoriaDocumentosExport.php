<?php

namespace App\Exports;

use App\Models\Documento;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AuditoriaDocumentosExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.AuditoriaDocuments', [
            'documentos' => Documento::all()
        ]);
    }
}
