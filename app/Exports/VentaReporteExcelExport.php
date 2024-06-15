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
        protected $idSucursalEvento;
        protected $fechaInicial;
        protected $fechaFinal;
        protected $titleLabel;

        public function __construct( $idSucursalEvento, $titleLabel, $fecIni, $fecFin) {
            $this->idSucursalEvento = $idSucursalEvento;
            $this->titleLabel = $titleLabel;
            $this->fechaInicial = $fecIni;
            $this->fechaFinal = $fecFin;
        }

        public function query()
        {
            // dd($this->idSucursalEvento."--".$this->titleLabel."--".$this->fechaInicial."--".$this->fechaFinal);
            $columnas = 'detalle_ventas.descripcion,
                         detalle_ventas.precio_unitario,
                         detalle_ventas.cantidad,
                         venta.descuento,
                         tipo_pagos.tipo,
                         venta.efectivo_recibido ,
                         venta.envio,
                         venta.referencia,
                         venta.observacion,
                         venta.created_at, 
                         users.username as nombre_usuario,';

            if (strtolower($this->titleLabel) == strtolower("Sucursal")) 
            {
                $columnas = $columnas . '
                         sucursals.direccion,
                         sucursals.ciudad';
            } else {
                $columnas = $columnas . '
                                        eventos.nombre,
                                        eventos.fecha_evento';
            }

            $consulta = Venta::query()
                            ->selectRaw($columnas)
                            ->join('detalle_ventas', 'detalle_ventas.id_venta', 'venta.id')
                            ->join('users', 'users.id', 'venta.id_usuario')
                            ->join('tipo_pagos', 'tipo_pagos.id', 'venta.id_tipo_pago');
            
            if (strtolower($this->titleLabel) == strtolower("Sucursal")) {
                $consulta = $consulta->join('sucursals', 'sucursals.id', 'venta.id_sucursal')
                                     ->where('venta.id_sucursal', "$this->idSucursalEvento");
            } else {
                $consulta = $consulta->join('eventos', 'eventos.id', 'venta.id_evento')
                                     ->where('venta.id_evento', "$this->idSucursalEvento");
            }

            $consulta = $consulta->where('venta.created_at','>=', "$this->fechaInicial")
                                ->where('venta.created_at','<=', "$this->fechaFinal")
                                ->where('venta.estado',1);
              
            return $consulta;
        }

        public function headings(): array
        {
            return [
                "Sucursal/Evento",      //A
                "Fecha Hora",           //B
                "Producto",             //C
                "P/U [Bs]",             //D
                "Cantidad",             //E    
                "Descuento[Bs]",         //F
                "Monto Recibido",       //G    
                "Tipo Pago",            //H
                "Vendedor",             //I
                "Envio" ,               //J
                "Referencia",           //K
                "observacion"           //L    
            ];
        }

        public function map($invoice): array
        {
            // dd($invoice->direccion);
            return [
                $invoice->direccion != "" ? substr($invoice->direccion,0,45)."..." : $invoice->nombre,
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
