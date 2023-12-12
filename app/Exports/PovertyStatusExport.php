<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PovertyStatusExport implements WithMultipleSheets
{
    use Exportable;
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new PovertyStatusSummarySheet();
        $sheets[] = new StudentListSheet('1', 'Below Poverty Line', 1, 'poverty');
        $sheets[] = new StudentListSheet('1', 'Above Poverty Line', 2, 'poverty');

        return $sheets;
    }
}

class PovertyStatusSummarySheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    public function headings(): array
    {
        return [
            ['Republic of the Philippines'],
            ['ISABELA STATE UNIVERSITY'],
            ['Santiago, Isabela'],
            [''],
//            ['No', 'Student Number', 'Name', 'Course', 'Year', 'Section']
            ['SUMMARY OF POVERTY STATUS'],
            [''],
            [''],
            ['Poverty Status','1st Year', '2nd Year', '3rd Year', '4th Year', 'Total'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],

            ],
            'A2' =>[
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'bold' => true
                ]
            ],
            'A3' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            'A5' =>[
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'bold' => true
                ]
            ],
            'A8:G8' => [
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'A6A6A6'],
                ],
                'font' => [
                    'bold' => true
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => Color::COLOR_BLACK],
                    ],
                ],
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->mergeCells('A2:G2');
                $event->sheet->getDelegate()->mergeCells('A3:G3');
                $event->sheet->getDelegate()->mergeCells('A5:G5');

                $event->sheet->setCellValue('B'.($event->sheet->getHighestRow() + 2), 'Prepared by: ');
                $event->sheet->setCellValue('F'.($event->sheet->getHighestRow()), 'Noted by: ');

                $event->sheet->setCellValue('B'.($event->sheet->getHighestRow() + 3), '');
                $event->sheet->setCellValue('F'.($event->sheet->getHighestRow()), '');
                $event->sheet->getStyle('B'.($event->sheet->getHighestRow()))->applyFromArray(['font' => [
                    'bold' => true,
                ]]);
                $event->sheet->getStyle('F'.($event->sheet->getHighestRow()))->applyFromArray(['font' => [
                    'bold' => true,
                ]]);

                $event->sheet->setCellValue('B'.($event->sheet->getHighestRow() + 1), 'Campus OSA Coordinator');
                $event->sheet->setCellValue('F'.($event->sheet->getHighestRow()), 'Campus Coordinator');

            }
        ];
    }

    public function title(): string
    {
        return "Summary";
    }

    public function collection()
    {
        $students = Student::whereYear('created_at', date('Y'))->get();
        $courseCount = 2; // Assuming there are 2 courses
        $yearCount = 5; // Assuming there are 5 years

        $bsitAbove = [];
        $bsitBelow = [];
        $bsaAbove = [];
        $bsaBelow = [];

        for($i = 1; $i <= $yearCount; $i++){
            $bsitAbove[$i] = $students->where('program', 1)->where('year', $i)->filter(function ($student){
                return $student->povertyStatus() === 'Above Poverty Line';
            })->count();
            $bsitBelow[$i] = $students->where('program', 1)->where('year', $i)->filter(function ($student){
                return $student->povertyStatus() === 'Below Poverty Line';
            })->count();
            $bsaAbove[$i] = $students->where('program', 2)->where('year', $i)->filter(function ($student){
                return $student->povertyStatus() === 'Above Poverty Line';
            })->count();
            $bsaBelow[$i] = $students->where('program', 2)->where('year', $i)->filter(function ($student){
                return $student->povertyStatus() === 'Below Poverty Line';
            })->count();
        }

        return collect([
            [
                'type'=> 'Above Poverty Line',
                '1st Year' => ($bsitAbove[1] + $bsaAbove[1]),
                '2nd Year' => ($bsitAbove[2] + $bsaAbove[2]),
                '3rd Year' => ($bsitAbove[3] + $bsaAbove[3]),
                '4th Year' => ($bsitAbove[4] + $bsaAbove[4]),
                'total' => ($bsitAbove[1] + $bsaAbove[1]) + ($bsitAbove[2] + $bsaAbove[2]) + ($bsitAbove[3] + $bsaAbove[3]) + ($bsitAbove[4] + $bsaAbove[4]),
            ],
            [
                'type'=> 'Below Poverty Line',
                '1st Year' => ($bsitBelow[1] + $bsaBelow[1]),
                '2nd Year' => ($bsitBelow[2] + $bsaBelow[2]),
                '3rd Year' => ($bsitBelow[3] + $bsaBelow[3]),
                '4th Year' => ($bsitBelow[4] + $bsaBelow[4]),
                'total' => ($bsitBelow[1] + $bsaBelow[1]) + ($bsitBelow[2] + $bsaBelow[2]) + ($bsitBelow[3] + $bsaBelow[3]) + ($bsitBelow[4] + $bsaBelow[4]),
            ]
        ]);
    }
}
