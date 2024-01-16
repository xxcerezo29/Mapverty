<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MalnutritionStatusExport implements WithMultipleSheets
{
    use Exportable;
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new MalnutritionSummarySheet();
        $sheets[] = new StudentListSheet('1', 'All', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('1', 'Very severely underweight', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('1', 'Severely underweight', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('1', 'Underweight', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('1', 'Normal (healthy weight)', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('1', 'Overweight', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('1', 'Obese Class I (Moderately obese)', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('1', 'Obese Class II (Severely obese)', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('1', 'Obese Class III (Very severely obese)', 1, 'malnutrition');

//      // BSA
        $sheets[] = new StudentListSheet('2', 'All', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('2', 'Very severely underweight', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('2', 'Severely underweight', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('2', 'Underweight', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('2', 'Normal (healthy weight)', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('2', 'Overweight', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('2', 'Obese Class I (Moderately obese)', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('2', 'Obese Class II (Severely obese)', 1, 'malnutrition');
        $sheets[] = new StudentListSheet('2', 'Obese Class III (Very severely obese)', 1, 'malnutrition');


        return $sheets;
    }
}
class MalnutritionSummarySheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithEvents, ShouldAutoSize
{
    public function collection()
    {
        $verySeverelyUnderweight = $this->getStudentByMalnutritionStatus(2);
        $severelyUnderweight = $this->getStudentByMalnutritionStatus(3);
        $underweight = $this->getStudentByMalnutritionStatus(4);
        $normal = $this->getStudentByMalnutritionStatus(5);
        $overweight = $this->getStudentByMalnutritionStatus(6);
        $obese1 = $this->getStudentByMalnutritionStatus(7);
        $obese2 = $this->getStudentByMalnutritionStatus(8);


        $data = collect([
            [
                'type' => 'Very severely underweight',
                '1st Year' =>$verySeverelyUnderweight->filter(function ($student){
                    return $student->year === 1;
                })->count(),
                '2nd Year' => $verySeverelyUnderweight->filter(function ($student){
                    return $student->year === 2;
                })->count(),
                '3rd Year' => $verySeverelyUnderweight->filter(function ($student){
                    return $student->year === 3;
                })->count(),
                '4th Year' => $verySeverelyUnderweight->filter(function ($student){
                    return $student->year === 4;
                })->count(),
                'Total' => $verySeverelyUnderweight->count(),
            ],
            [
                'type' => 'Severely underweight',
                '1st Year' =>$severelyUnderweight->filter(function ($student){
                    return $student->year === 1;
                })->count(),
                '2nd Year' => $severelyUnderweight->filter(function ($student){
                    return $student->year === 2;
                })->count(),
                '3rd Year' => $severelyUnderweight->filter(function ($student){
                    return $student->year === 3;
                })->count(),
                '4th Year' => $severelyUnderweight->filter(function ($student){
                    return $student->year === 4;
                })->count(),
                'Total' => $severelyUnderweight->count(),

            ],
            [
                'type' => 'Underweight',
                '1st Year' =>$underweight->filter(function ($student){
                    return $student->year === 1;
                })->count(),
                '2nd Year' => $underweight->filter(function ($student){
                    return $student->year === 2;
                })->count(),
                '3rd Year' => $underweight->filter(function ($student){
                    return $student->year === 3;
                })->count(),
                '4th Year' => $underweight->filter(function ($student){
                    return $student->year === 4;
                })->count(),
                'Total' => $underweight->count(),

            ],
            [
                'type' => 'Normal (healthy weight)',
                '1st Year' =>$normal->filter(function ($student){
                    return $student->year === 1;
                })->count(),
                '2nd Year' => $normal->filter(function ($student){
                    return $student->year === 2;
                })->count(),
                '3rd Year' => $normal->filter(function ($student){
                    return $student->year === 3;
                })->count(),
                '4th Year' => $normal->filter(function ($student){
                    return $student->year === 4;
                })->count(),
                'Total' => $normal->count(),
            ],
            [
                'type' => 'Overweight',
                '1st Year' =>$overweight->filter(function ($student){
                    return $student->year === 1;
                })->count(),
                '2nd Year' => $overweight->filter(function ($student){
                    return $student->year === 2;
                })->count(),
                '3rd Year' => $overweight->filter(function ($student){
                    return $student->year === 3;
                })->count(),
                '4th Year' => $overweight->filter(function ($student){
                    return $student->year === 4;
                })->count(),
                'Total' => $overweight->count(),
            ],
            [
                'type' => 'Obese Class I (Moderately obese)',
                '1st Year' =>$obese1->filter(function ($student){
                    return $student->year === 1;
                })->count(),
                '2nd Year' => $obese1->filter(function ($student){
                    return $student->year === 2;
                })->count(),
                '3rd Year' => $obese1->filter(function ($student){
                    return $student->year === 3;
                })->count(),
                '4th Year' => $obese1->filter(function ($student){
                    return $student->year === 4;
                })->count(),
                'Total' => $obese1->count(),
            ],
            [
                'type' => 'Obese Class II (Severely obese)',
                '1st Year' =>$obese2->filter(function ($student){
                    return $student->year === 1;
                })->count(),
                '2nd Year' => $obese2->filter(function ($student){
                    return $student->year === 2;
                })->count(),
                '3rd Year' => $obese2->filter(function ($student){
                    return $student->year === 3;
                })->count(),
                '4th Year' => $obese2->filter(function ($student){
                    return $student->year === 4;
                })->count(),
                'Total' => $obese2->count(),
            ]
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            ['Republic of the Philippines'],
            ['ISABELA STATE UNIVERSITY'],
            ['Santiago, Isabela'],
            [''],
//            ['No', 'Student Number', 'Name', 'Course', 'Year', 'Section']
            ['SUMMARY OF MALNUTRITION STATUS'],
            [''],
            [''],
            ['Malnutrition Status','1st Year', '2nd Year', '3rd Year', '4th Year', 'Total'],
        ];
    }

    public function title(): string
    {
        return "Summary";
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

    public function getStudentByMalnutritionStatus($filteredType)
    {
        $bmiRanges = [
            2 => ['max' => 15],
            3 => ['min' => 16, 'max' => 18],
            4 => ['min' => 18.5, 'max' => 25],
            5 => ['min' => 25, 'max' => 30],
            6 => ['min' => 30, 'max' => 35],
            7 => ['min' => 35, 'max' => 40],
            8 => ['min' => 40],
        ];

        $students = Student::whereYear('created_at', "2023")->get();

        if(isset($bmiRanges[$filteredType])){
            $filter = $bmiRanges[$filteredType];

            $filteredSStudent = $students->filter(function ($student) use ($filter){
                if(isset($filter['min']) && isset($filter['max'])){
                    return $student->BMI() < $filter['max'] && $student->BMI() > $filter['min'];
                }else if(isset($filter['min'])){
                    return $student->BMI() > $filter['min'];
                }else {
                    return $student->BMI() < $filter['max'];
                }
            })->reject(function ($student){
                return $student->BMI() === 0;
            });

            return $filteredSStudent;
        }

        return [];
    }
}

