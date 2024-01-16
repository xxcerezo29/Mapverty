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

class StudentListExport implements WithMultipleSheets
{
    use Exportable;
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new StudentSummarySheet();
        $sheets[] = new StudentListSheet('1', 'First Year', 1, 'list', 1);
        $sheets[] = new StudentListSheet('2', 'First Year', 2, 'list', 1);
        $sheets[] = new StudentListSheet('1', 'Second Year', 1, 'list', 2);
        $sheets[] = new StudentListSheet('2', 'Second Year', 2, 'list', 2);
        $sheets[] = new StudentListSheet('1', 'Third Year', 1, 'list', 3);
        $sheets[] = new StudentListSheet('2', 'Third Year', 2, 'list', 3);
        $sheets[] = new StudentListSheet('1', 'Fourth Year', 1, 'list', 4);
        $sheets[] = new StudentListSheet('2', 'Fourth Year', 2, 'list', 4);

        return  $sheets;

    }
}

class StudentSummarySheet implements FromCollection, WithTitle, WithHeadings,  WithStyles,  WithEvents
{

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
            ['SUMMARY OF STUDENT PER COLLEGES'],
            [''],
            [''],
            ['Program','1', '2', '3', '4', 'Total'],
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

    public function collection()
    {
        $bsit1 = Student::where('program', 1)->where('year', 1)->whereYear('created_at', "2023")->get();
        
        $bsit2= Student::where('program', 1)->where('year', 2)->whereYear('created_at', "2023")->get();
        $bsit3= Student::where('program', 1)->where('year', 3)->whereYear('created_at', "2023")->get();
        $bsit4= Student::where('program', 1)->where('year', 4)->whereYear('created_at', "2023")->get();

        $bsa1 = Student::where('program', 2)->where('year', 1)->whereYear('created_at', "2023")->get();
        $bsa2= Student::where('program', 2)->where('year', 2)->whereYear('created_at', "2023")->get();
        $bsa3= Student::where('program', 2)->where('year', 3)->whereYear('created_at', "2023")->get();
        $bsa4= Student::where('program', 2)->where('year', 4)->whereYear('created_at', "2023")->get();

        $data = collect([
            [
                'course'=> 'BSIT',
                'first'=> $bsit1->count(),
                'second'=>$bsit2->count(),
                'third'=>$bsit3->count(),
                'fourth'=>$bsit4->count(),
                'total'=>$bsit1->count()+$bsit2->count()+$bsit3->count()+$bsit4->count()
            ],
            [
                'course'=> 'BSA',
                'first'=>$bsa1->count(),
                'second'=> $bsa2->count(),
                'third'=>$bsa3->count(),
                'fourth'=>$bsa4->count(),
                'total'=> $bsa1->count()+$bsa2->count()+$bsa3->count()+$bsa4->count()
            ]
        ]);

        return $data;
    }
}
