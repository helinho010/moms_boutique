<?php

namespace App\Exports;

use App\Models\compras;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;

class ComprasExport implements FromCollection, WithStrictNullComparison, WithHeadings, WithColumnWidths, WithMapping
{
    protected $invoice;
    protected $detalle;

    public function __construct($id_sucursal, $fecha_inicio, $fecha_fin, $detalleExport)
    {
        $this->invoice = [$id_sucursal, $fecha_inicio, $fecha_fin];
        $this->detalle = $detalleExport;
    }   

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 25,
            'D' => 8,
            'E' => 8,
            'F' => 8,
            'G' => 8,
            'H' => 20,
            'I' => 20,
            'J' => 20,
        ];
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Sucursal Destino',
            'Descripcion Producto',
            'Cantidad',
            'Precio Unitario',
            'IVA',
            'Sub Total',
            'Usuario Solicitante',
            'Usuario Aprobador',
            'Usuario Rechazador',
            'Usuario Envio BD',
            'Fecha Creacion',

        ];
    }

    public function map($invoice): array
    {
        static $i = 1;
        return [
            $i++,
            $invoice->id_sucursal_destino,
            $invoice->descripcion,
            $invoice->cantidad,
            $invoice->precio_unitario,
            $invoice->iva,
            $invoice->sub_total,
            $invoice->nombre_usuario_creador,
            $invoice->nombre_usuario_aprobador,
            $invoice->nombre_usuario_rechazador,
            $invoice->nombre_usuario_enviobd,
            $invoice->fecha_creacion_compra,
        ];
    }

    public function collection()
    {
        // return compras::all();
        return collect($this->detalle);
    }
}
