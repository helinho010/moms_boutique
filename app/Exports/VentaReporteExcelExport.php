<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;


use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class VentaReporteExcelExport implements FromQuery, WithHeadings, WithColumnWidths, WithColumnFormatting, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
        protected $idSucursal;
        protected $fechaInicial;
        protected $fechaFinal;

        public function __construct( $id_suc, $fecIni, $fecFin) {
            $this->idSucursal = $id_suc;
            $this->fechaInicial = $fecIni;
            $this->fechaFinal = $fecFin;
        }

        public function query()
        {
            // dd("--".$this->idSucursal . "--".  $this->fechaInicial ."--". $this->fechaFinal);
            return Venta::query()
                        ->selectRaw('	
                                    sucursals.direccion,
                                    sucursals.ciudad,
                                    detalle_ventas.descripcion,
                                    detalle_ventas.precio_unitario,
                                    detalle_ventas.cantidad,
                                    venta.descuento,
                                    tipo_pagos.tipo,
                                    venta.efectivo_recibido ,
                                    venta.envio,
                                    venta.referencia,
                                    venta.observacion,
                                    venta.created_at, 
                                    users.username as nombre_usuario
                                    ')
                        ->join('sucursals', 'sucursals.id', 'venta.id_sucursal')
                        ->join('detalle_ventas', 'detalle_ventas.id_venta', 'venta.id')
                        ->join('users', 'users.id', 'venta.id_usuario')
                        ->join('tipo_pagos', 'tipo_pagos.id', 'venta.id_tipo_pago')
                        ->where('venta.id_sucursal', "$this->idSucursal")
                        ->where('venta.created_at','>=', "$this->fechaInicial")
                        ->where('venta.created_at','<=', "$this->fechaFinal")
                        ->where('venta.estado',1);
        }

        public function headings(): array
        {
                    // A           B            C          D        E            F                G              H            I          J           K               L
            return ["Sucursal", "Fecha Hora", "Producto", "P/U", "Cantidad", "Descuento[%]", "Monto Recibido","Tipo Pago", "Vendedor", "Envio" , "Referencia", "observacion" ];
        }

        public function map($invoice): array
        {
            return [
                substr($invoice->direccion,0,45)."..." ,
                $invoice->created_at,
                $invoice->descripcion,
                $invoice->precio_unitario,
                $invoice->cantidad,
                $invoice->descuento,
                $invoice->efectivo_recibido, 
                $invoice->tipo,
                $invoice->nombre_usuario,
                $invoice->envio,
                $invoice->referencia,
                $invoice->observacion,
                // $invoice->ciudad,
                // Date::dateTimeToExcel($invoice->updated_at),
                
            ];
        }

        public function columnFormats(): array
        {
            return [
                // 'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            ];
        }

        public function columnWidths(): array
        {
            return [
                'A' => 30,
                'B' => 20,
                'C' => 30,
                'D' => 10,
                'E' => 10,
                'F' => 16,
                'G' => 18,
                'H' => 18,
                'I' => 13,
                'J' => 13, 
                'K' => 15,   
                'L' => 20, 
            ];
        }

        public function styles(Worksheet $sheet)
        {
            return [
                // Style the first row as bold text.
                1    => ['font' => ['bold' => true, 'size'=>11], 'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],],
    
                // Styling a specific cell by coordinate.
                // 'B2' => ['font' => ['italic' => true]],
    
                // Styling an entire column.
                // 'C'  => ['font' => ['size' => 16]],
            ];
        }


}
