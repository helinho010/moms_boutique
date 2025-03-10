<?php

namespace App\Exports;

use App\Models\Caja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CierreCajaExport implements FromCollection, WithStrictNullComparison, WithHeadings, WithColumnWidths
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
            'G' => 15,
            'H' => 15,
            'I' => 30,
            'J' => 15,
            'K' => 6,
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
            'QR',
            'Venta Sistema',
            'Total Declarado',
            'Observacion',
            'Usuario',
            'Verificado',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->invoice;
    }
}
