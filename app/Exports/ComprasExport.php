<?php

namespace App\Exports;

use App\Models\compras;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ComprasExport implements FromCollection, WithStrictNullComparison, WithHeadings, WithColumnWidths
{
    protected $invoice;

    public function __construct($dataExport)
    {
        $this->invoice = $dataExport;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 15,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 15,
            'I' => 15,
            'J' => 30,
            'K' => 15,
            'L' => 6,
        ];
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Sucursal',
            'Fecha Cierre',
            'Efectivo',
            'Tarjeta',
            'Transferencia',
            'QR',
            'Venta Sistema',
            'Total Declarado',
            'Observacion',
            'Usuario',
            'Verificado',
        ];
    }

    public function collection()
    {
        return compras::all();
    }
}
