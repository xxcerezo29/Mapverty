<?php

namespace App\Exports;

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

class FirstGenerationStudentExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new FirstGenerationSummarySheet();
        $sheets[] = new StudentListSheet('1', 'Yes', 1, 'firstgeneration', null, 1);
        $sheets[] = new StudentListSheet('2', 'Yes', 2, 'firstgeneration', null, 1);
        $sheets[] = new StudentListSheet('1', 'Yes', 1, 'firstgeneration', null, 2);
        $sheets[] = new StudentListSheet('2', 'Yes', 2, 'firstgeneration', null, 2);

        $sheets[] = new StudentListSheet('1', 'No', 1, 'firstgeneration', null, 1);
        $sheets[] = new StudentListSheet('2', 'No', 2, 'firstgeneration', null, 1);
        $sheets[] = new StudentListSheet('1', 'No', 1, 'firstgeneration', null, 2);
        $sheets[] = new StudentListSheet('2', 'No', 2, 'firstgeneration', null, 2);

        return  $sheets;
    }
}

class FirstGenerationSummarySheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        $students = \App\Models\Student::whereYear('created_at', "2023")->get();
        return collect([
            [
                'type' => 'Male',
                '1st Year' => $students->filter(function ($student){
                    if($student->firstGeneration() == 'Yes')
                    {
                        return $student->year == 1 && $student->info->sex == 1;
                    }
                })->reject(function ($student){
                    return $student->firstGeneration() == 'Empty';
                })->count(),
                '2nd Year' => $students->filter(function ($student){
                    if($student->firstGeneration() == 'Yes')
                    {
                        return $student->year == 2 && $student->info->sex  == 1;
                    }
                })->reject(function ($student){
                    return $student->firstGeneration() == 'Empty';
                })->count(),
                '3rd Year' => $students->filter(function ($student){
                    if($student->firstGeneration() == 'Yes')
                    {
                        return $student->year == 3 && $student->info->sex  == 1;
                    }
                })->reject(function ($student){
                    return $student->firstGeneration() == 'Empty';
                })->count(),
                '4th Year' => $students->filter(function ($student){
                    if($student->firstGeneration() == 'Yes')
                    {
                        return $student->year == 4 && $student->info->sex  == 1;
                    }
                })->reject(function ($student){
                    return $student->firstGeneration() == 'Empty';
                })->count(),
            ],
            [
                'type' => 'Female',
                '1st Year' => $students->filter(function ($student){
                    if($student->firstGeneration() == 'Yes')
                    {
                        return $student->year == 1 && $student->info->sex == 2;
                    }
                })->reject(function ($student){
                    return $student->firstGeneration() == 'Empty';
                })->count(),
                '2nd Year' => $students->filter(function ($student){
                    if($student->firstGeneration() == 'Yes')
                    {
                        return $student->year == 2 && $student->info->sex  == 2;
                    }
                })->reject(function ($student){
                    return $student->firstGeneration() == 'Empty';
                })->count(),
                '3rd Year' => $students->filter(function ($student){
                    if($student->firstGeneration() == 'Yes')
                    {
                        return $student->year == 3 && $student->info->sex  == 2;
                    }
                })->reject(function ($student){
                    return $student->firstGeneration() == 'Empty';
                })->count(),
                '4th Year' => $students->filter(function ($student){
                    if($student->firstGeneration() == 'Yes')
                    {
                        return $student->year == 4 && $student->info->sex  == 2;
                    }
                })->reject(function ($student){
                    return $student->firstGeneration() == 'Empty';
                })->count(),
            ]
        ]);
    }

    public function title(): string
    {
        return "Summary";
    }

    public function headings(): array
    {
        return [
            ['Republic of the Philippines'],
            ['ISABELA STATE UNIVERSITY'],
            ['Santiago, Isabela'],
            [''],
//            ['No', 'Student Number', 'Name', 'Course', 'Year', 'Section']
            ['SUMMARY OF FIRST GENERATION'],
            [''],
            [''],
            ['SEX', '1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year', 'Total'],
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
            'A2' => [
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
            'A5' => [
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
                    'fillType' => Fill::FILL_SOLID,
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
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->mergeCells('A2:G2');
                $event->sheet->getDelegate()->mergeCells('A3:G3');
                $event->sheet->getDelegate()->mergeCells('A5:G5');

                $event->sheet->setCellValue('B' . ($event->sheet->getHighestRow() + 2), 'Prepared by: ');
                $event->sheet->setCellValue('F' . ($event->sheet->getHighestRow()), 'Noted by: ');

                $event->sheet->setCellValue('B' . ($event->sheet->getHighestRow() + 3), '');
                $event->sheet->setCellValue('F' . ($event->sheet->getHighestRow()), '');
                $event->sheet->getStyle('B' . ($event->sheet->getHighestRow()))->applyFromArray(['font' => [
                    'bold' => true,
                ]]);
                $event->sheet->getStyle('F' . ($event->sheet->getHighestRow()))->applyFromArray(['font' => [
                    'bold' => true,
                ]]);

                $event->sheet->setCellValue('B' . ($event->sheet->getHighestRow() + 1), 'Campus OSA Coordinator');
                $event->sheet->setCellValue('F' . ($event->sheet->getHighestRow()), 'Campus Coordinator');

            }
        ];
    }
}
