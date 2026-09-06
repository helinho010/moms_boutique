<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;

class ComprasExport implements FromCollection, WithStrictNullComparison, WithHeadings, WithColumnWidths, WithMapping, WithCustomStartCell, WithEvents
{

    protected $invoice;
    protected $detalle;

    public function __construct($id_sucursal, $direccion_sucursal, $fecha_inicio, $fecha_fin, $detalleExport)
    {
        $this->invoice = [$id_sucursal, $direccion_sucursal, $fecha_inicio, $fecha_fin];
        $this->detalle = $detalleExport;
    }

    public function registerEvents(): array
    {
        return [
                
                BeforeWriting::class => function (BeforeWriting $event) {
                $sheet = $event->getDelegate()->getActiveSheet();
                $sheet->setCellValue('B1', 'Sucursal:'); 
                $sheet->setCellValue('C1', $this->invoice[1]); 
                $sheet->setCellValue('B2', 'Fecha Inicio:'); 
                $sheet->setCellValue('C2', $this->invoice[2]); 
                $sheet->setCellValue('D2', 'Fecha Fin:'); 
                $sheet->setCellValue('E2', $this->invoice[3]); 
            },
        ];
    }
    
    public function startCell(): string
    {
        return 'A4';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 8,
            'D' => 8,
            'E' => 8,
            'F' => 8,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 10,
            'L' => 8,
        ];
    }

    public function headings(): array
    {
        return [
            'Nro',
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
            'Codigo Compra',
        ];
    }

    public function map($invoice): array
    {
        static $i = 1;
        return [
            $i++,
            $invoice->descripcion,
            $invoice->cantidad,
            $invoice->precio_unitario,
            $invoice->iva / 100,
            $invoice->sub_total / 100,
            $invoice->nombre_usuario_creador,
            $invoice->nombre_usuario_aprobador,
            $invoice->nombre_usuario_rechazador ? $invoice->nombre_usuario_rechazador : 'N/A',
            $invoice->nombre_usuario_enviobd,
            $invoice->fecha_creacion_compra,
            $invoice->codigo_compra,
        ];
    }

    public function collection()
    {
        // return compras::all();
        return collect($this->detalle);
    }
}
