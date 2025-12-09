<?php

namespace App\Exports;

use App\Models\InventarioInterno;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InventarioInternoExport implements FromCollection, WithStrictNullComparison,
                                         WithHeadings, WithMapping, 
                                         WithStyles, WithColumnWidths
{

    protected $inventario;

    public function __construct($inventario)
    {
        $this->inventario = $inventario;
    }

    public function headings(): array
        {
            return [
                "Nro.",                //A
                "Categoria",           //B
                "Producto",            //C
                "Talla",               //D
                "Costo [Bs]",          //E
                "Precio [Bs]",         //F
                "Tipo Ing. Sal.",      //G
                "Stock",               //H
                "Usuario",             //I    
                "Estado",              //J
            ];
        }
    public function columnWidths(): array
    {
            return [
                'A' => 5,
                'B' => 12,
                'C' => 27,
                'D' => 12,
                'E' => 11,
                'F' => 11,
                'G' => 13,
                'H' => 8, 
                'I' => 8,
            ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->correlativo,
            $invoice->categoria,
            $invoice->nombre_productos,
            $invoice->talla_productos != "" ? $invoice->talla_productos : "ST (Sin Talla)",
            $invoice->costo_productos,
            $invoice->precio_productos,
            $invoice->tipo_tipo_ingreso_salidas,
            $invoice->stock != 0 ? $invoice->stock:"0",
            $invoice->name_users,
            $invoice->estado_inventario_internos == 1 ? "Activo":"Inactivo",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                    'font' => ['bold' => true, 'size' => 11], 
                    'alignment' => [
                                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                                    'vertical' => Alignment::VERTICAL_CENTER,
                                    'wrapText' => true,
                                   ],
                 ],

            'C' => [
                     'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                     ],
                   ],
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $inventarioConIndice = $this->inventario->map(function ($item, $key) {
            $item->correlativo = $key + 1; // Añadir la propiedad 'correlativo' directamente al objeto
            return $item;
        });
        return $inventarioConIndice;
    }
}
