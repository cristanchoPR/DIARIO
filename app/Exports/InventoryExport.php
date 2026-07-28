<?php

namespace App\Exports;

use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InventoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithDrawings, WithCustomStartCell, WithEvents
{
    protected $inventario;

    public function __construct(Inventario $inventario)
    {
        $this->inventario = $inventario;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->inventario->detalles()->with('producto')->get();
    }

    public function headings(): array
    {
        return [
            'Código Interno',
            'Código de Barras',
            'Producto',
            'Categoría',
            'Marca',
            'Unidad de Medida',
            'Existencia Sistema',
            'Costo Sistema',
            'Cantidad Física',
            'Costo Conteo',
            'Valor Total',
            'Diferencia Unidades',
            'Diferencia Dinero',
        ];
    }

    /**
    * @var \App\Models\InventarioDetalle $row
    */
    public function map($row): array
    {
        $diffU = $row->cantidad_fisica !== null ? ($row->cantidad_fisica - $row->existencia_sistema) : 0;
        $diffD = $row->cantidad_fisica !== null ? ($row->valor_total - ($row->existencia_sistema * $row->costo_sistema)) : 0;

        return [
            $row->producto->codigo,
            $row->producto->codigo_barras,
            $row->producto->nombre,
            $row->producto->categoria,
            $row->producto->marca,
            $row->producto->unidad_medida,
            (float) $row->existencia_sistema,
            (float) $row->costo_sistema,
            $row->cantidad_fisica !== null ? (float) $row->cantidad_fisica : 'No Contado',
            $row->costo_contado !== null ? (float) $row->costo_contado : (float) $row->costo_sistema,
            (float) $row->valor_total,
            $diffU,
            $diffD,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0a1d37']]],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Aldia Oficial');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('images/logo_aldia_oficial_cropped.png'));
        $drawing->setHeight(75);
        $drawing->setOffsetX(60);
        $drawing->setOffsetY(10);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function startCell(): string
    {
        return 'A6'; // Give a bit of space for the logo
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Hide default gridlines for a cleaner look
                $sheet->setShowGridlines(false);

                // Merge cells A1 to C5 for the logo
                $sheet->mergeCells('A1:C5');
                
                // 1) Set white background for the logo area (A1:C5)
                $sheet->getStyle('A1:C5')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF']
                    ]
                ]);

                // 2) Set dark blue background for the rest of the top area (D1:M5)
                $sheet->getStyle('D1:M5')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0a1d37'] // Matches the dark blue from screenshot
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '16305C'] // subtle border
                        ]
                    ]
                ]);

                // 3) Modern styling for the table data
                $highestRow = $sheet->getHighestRow();
                
                // Add borders to the table rows (Starts at 6 due to startCell A6)
                $sheet->getStyle('A6:M' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E2E8F0'] // Light slate color
                        ]
                    ]
                ]);

                // Zebra striping for the rows (Starts at 7 because 6 is headers)
                for ($row = 7; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        // Even rows: white
                        $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FFFFFF']
                            ]
                        ]);
                    } else {
                        // Odd rows: very light slate/gray
                        $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8FAFC']
                            ]
                        ]);
                    }
                }
            }
        ];
    }
}
