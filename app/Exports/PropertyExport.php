<?php

namespace App\Exports;

use App\Constants\Constants;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PropertyExport implements FromView
{
    private $view;
//    private $collection;

    /**
     * ProvidentFundStatementExport constructor.
     * @param $collection
     * @param string $view
     */
    public function __construct(view $view)
    {
        $this->view = $view;
    }

    /**
     * @return array
     */
    private function pageStyles(){
        $color = Constants::PAYSLIP_COLOR;
        $color = substr($color, 1, strlen($color));
        $styles = [
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => "FFFFFFFF",
                ],
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => "$color"],
                ],
                'allborders' => [
                    'style' => Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => "$color",
                ],
                'endColor' => [
                    'argb' => "$color",
                ],
            ],
        ];
        return $styles ;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        $styles = $this->pageStyles() ;
        return [
            AfterSheet::class => function(AfterSheet $event) use ($styles) {
                $event->sheet->getDelegate()->insertNewColumnBefore('A', 1);
                $event->sheet->getDelegate()->getStyle('B6:K6')->applyFromArray($styles);
                $event->sheet->getDelegate()->getStyle('B6:B10')->applyFromArray($styles);
                $event->sheet->getDelegate()->getStyle('D6:D10')->applyFromArray($styles);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('G')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('H')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('I')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('J')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('K')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(40);
                $event->sheet->getDelegate()->getStyle('E10')
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('C16:C999')
                    ->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('D16:D999')
                    ->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('E16:D999')
                    ->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('F16:D999')
                    ->getAlignment()->setWrapText(true);
            }
        ];
    }


    public function view(): View
    {
        return $this->view;
    }
}
