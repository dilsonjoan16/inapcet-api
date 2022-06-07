<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AuditoriaUserExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.AuditoriaUsers', [
            'usuarios' => User::all()
        ]);
    }
}
