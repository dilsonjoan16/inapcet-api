<?php

namespace App\Exports;

use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ProyectosExport implements FromQuery, ShouldAutoSize, WithHeadings
{
    use Exportable;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

     public function headings(): array
    {
        return [
            'Id',
            'Nombre',
            'Descripcion',
            'Duracion estimada en semanas',
            'Presupuesto estimado en Bs.', //Presupuesto en $
            'Etapa del proyecto', //Etapa del Proyecto
            'Estado (1 = Activo) (0 = Inactivo)',
        ];
    }

    public function query()
    {
        return Proyecto::query()->where('id', $this->id);
    }
}
