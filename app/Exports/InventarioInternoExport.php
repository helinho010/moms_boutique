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

class InventarioInternoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{

    protected $inventario;

    public function __construct($inventario)
    {
        $this->inventario = $inventario;
    }

    public function headings(): array
        {
            return [
                "Nro.",                   //A
                "Producto",            //B
                "Tipo Ing. Sal.",      //C
                "Stock",               //D
                "Usuario",             //E    
                "Estado",               //F
            ];
        }
    public function columnWidths(): array
    {
            return [
                'A' => 5,
                'B' => 25,
                'C' => 13,
                'D' => 8,
                'E' => 10,
                'F' => 10, 
            ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->correlativo,
            $invoice->nombre_productos,
            $invoice->tipo_tipo_ingreso_salidas,
            $invoice->stock_inventario_internos != 0 ? $invoice->stock_inventario_internos:"0",
            $invoice->name_users,
            $invoice->estado == 1 ? "Activo":"Inactivo",
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
