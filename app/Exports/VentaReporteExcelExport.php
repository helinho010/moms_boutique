<?php

namespace App\Exports;

use App\Models\Venta;
use Dom\Text;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;


use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class VentaReporteExcelExport implements FromQuery, WithHeadings, WithColumnWidths, WithColumnFormatting, WithMapping, WithStyles, WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */
        protected $idSucursalEvento;
        protected $fechaInicial;
        protected $fechaFinal;
        protected $titleLabel;
        protected $numRegistrosConsulta = 0;

        public function __construct( $idSucursalEvento, $titleLabel, $fecIni, $fecFin) {
            $this->idSucursalEvento = $idSucursalEvento;
            $this->titleLabel = $titleLabel;
            $this->fechaInicial = $fecIni;
            $this->fechaFinal = $fecFin;
        }

        public function query()
        {
            // dd($this->idSucursalEvento."--".$this->titleLabel."--".$this->fechaInicial."--".$this->fechaFinal);
            $columnas = '
                         categorias.nombre as categoria,
                         detalle_ventas.descripcion,
                         detalle_ventas.precio_unitario,
                         detalle_ventas.cantidad,
                         detalle_ventas.descuento_item,
                         detalle_ventas.subtotal,
                         venta.descuento, 
                         tipo_pagos.tipo,
                         venta.efectivo_recibido,
                         venta.total_venta,
                         venta.numero_factura,
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
                            ->join('productos', 'productos.id', 'detalle_ventas.id_producto')
                            ->join('categorias', 'categorias.id', 'productos.id_categoria')
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
              
            $this->numRegistrosConsulta = $consulta->count();
            
            return $consulta;
        }

        public function headings(): array
        {
            return [
                "Sucursal/Evento",      //A
                "Fecha Hora",           //B
                "Categoria",            //C
                "Producto",             //D
                "P/U [Bs]",             //E
                "Cantidad",             //F    
                "Descuento[Bs]",        //G
                "Sub Total",            //H    
                "Tipo Pago",            //I
                "Vendedor",             //J
                "Numero Factura",       //K
                "Envio" ,               //L
                "Referencia",           //M
                "observacion"           //N    
            ];
        }

        public function map($invoice): array
        {
            return [
                $invoice->direccion != "" ? substr($invoice->direccion,0,45)."..." : $invoice->nombre,
                $invoice->created_at,
                $invoice->categoria,
                $invoice->descripcion,
                $invoice->precio_unitario,
                $invoice->cantidad,
                $invoice->descuento_item != 0 ? $invoice->descuento_item:0,
                $invoice->subtotal, 
                $invoice->tipo,
                $invoice->nombre_usuario,
                $invoice->numero_factura  === null ? "-":"$invoice->numero_factura",
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
                'E' => NumberFormat::FORMAT_NUMBER_00,
                'G' => NumberFormat::FORMAT_NUMBER_00,
                'H' => NumberFormat::FORMAT_NUMBER_00,
                'K' => NumberFormat::FORMAT_TEXT,
            ];
        }

        public function columnWidths(): array
        {
            return [
                'A' => 30,
                'B' => 20,
                'C' => 20,
                'D' => 30,
                'E' => 10,
                'F' => 16,
                'G' => 16,
                'H' => 18,
                'I' => 18,
                'J' => 20, 
                'K' => 15,   
                'L' => 20,
                'M' => 20,
                'N' => 30,
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
