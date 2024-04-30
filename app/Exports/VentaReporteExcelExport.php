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
                                    venta.descuento,
                                    tipo_pagos.tipo,
                                    venta.updated_at, 
                                    users.username as nombre_usuario
                                    ')
                        ->join('sucursals', 'sucursals.id', 'venta.id_sucursal')
                        ->join('detalle_ventas', 'detalle_ventas.id_venta', 'venta.id')
                        ->join('users', 'users.id', 'venta.id_usuario')
                        ->join('tipo_pagos', 'tipo_pagos.id', 'venta.id_tipo_pago')
                        ->where('venta.id_sucursal', "$this->idSucursal")
                        ->where('venta.updated_at','>=', "$this->fechaInicial")
                        ->where('venta.updated_at','<=', "$this->fechaFinal");
        }

        public function headings(): array
        {
            return ["Sucursal", "Cuiudad", "Producto","P/U", "Descuento[%]","Tipo Pago", "Fecha", "Usuario"];
        }

        public function map($invoice): array
        {
            return [
                substr($invoice->direccion,0,45)."..." ,
                $invoice->ciudad,
                $invoice->descripcion,
                $invoice->precio_unitario,
                $invoice->descuento,
                $invoice->tipo,
                // Date::dateTimeToExcel($invoice->updated_at),
                $invoice->updated_at,
                $invoice->nombre_usuario
            ];
        }

        public function columnFormats(): array
        {
            return [
                'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            ];
        }

        public function columnWidths(): array
        {
            return [
                'A' => 40,
                'B' => 10,
                'C' => 40,
                'E' => 13,
                'F' => 15,
                'G' => 20,            
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
