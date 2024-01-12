<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpParser\Node\Stmt\Switch_;

class StudentListSheet implements FromCollection, WithMapping, WithTitle, WithHeadings, WithStyles,WithEvents
{
    private $program;
    private $filteredType;
    private $exportType;
    private $index;
    private $year;
    private $sex;
    public function __construct($program, $filteredType, $index, $exportType = 'poverty', $year = null, $sex = null)
    {
        $this->program = $program;
        $this->filteredType = $filteredType;
        $this->index = $index;
        $this->exportType = $exportType;
        $this->year = $year;
        $this->sex = $sex;

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->exportType === 'list'){
            $students = Student::whereYear('created_at', "2023")
                ->where('program', $this->program)
                ->where('year', $this->year)
                ->get();

            return $students;
        }else if($this->exportType === 'firstgeneration'){
            $students = Student::whereYear('created_at', "2023")
                ->where('program', $this->program)
                ->get();
            return $students->filter(function ($student) {
                return $student->firstGeneration() === $this->filteredType && $student->info->sex === $this->sex;
            });
        }
            else{
            $students = Student::whereYear('created_at',  "2023")
                ->where('program', $this->program)
                ->get();
            return $students->filter(function ($student){
                if($this->exportType === 'poverty')
                    return $student->povertyStatus() === $this->filteredType;
                else if($this->exportType === 'malnutrition'){
                    $bmi = $student->BMI();
                    switch ($this->filteredType){
                        case 'All':
                            return true;
                        case 'Very severely underweight':
                            return $bmi < 15 && $bmi != 0;
                        case 'Severely underweight':
                            return $bmi >= 15 && $bmi < 16;
                        case 'Underweight':
                            return $bmi >= 16 && $bmi < 18.5;
                        case 'Normal (healthy weight)':
                            return $bmi >= 18.5 && $bmi < 25;
                        case 'Overweight':
                            return $bmi >= 25 && $bmi < 30;
                        case 'Obese Class I (Moderately obese)':
                            return $bmi >= 30 && $bmi < 35;
                        case 'Obese Class II (Severely obese)':
                            return $bmi >= 35 && $bmi < 40;
                        case 'Obese Class III (Very severely obese)':
                            return $bmi >= 40;
                        default:
                            return false;
                    }
                }
            });
        }

    }

    public function map($row): array
    {
        return [
            $this->index++,
            $row->student_number,
            $row->getFullNameAttribute(),
            config('enums.programs.' . $row->program),
            $row->section??' ',
            config('enums.years.' . $row->year),
        ];
    }

    public function title(): string
    {
        if($this->sex === null)
            return config('enums.programs.' . $this->program) . '-'.$this->filteredType;
        else 
            return config('enums.programs.' . $this->program) . '-'.$this->filteredType.' '.config('enums.sex.'.$this->sex);
    }

    public function headings(): array
    {
        return [
            ['Republic of the Philippines'],
            ['ISABELA STATE UNIVERSITY'],
            ['Santiago, Isabela'],
            [''],
//            ['No', 'Student Number', 'Name', 'Course', 'Year', 'Section']
            [config('enums.programs.' . $this->program)."-".$this->filteredType],
            [''],
            [''],
            ['No.', 'Student Number', 'Name', 'Program', 'Section', 'Year'],
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
                $event->sheet->setCellValue('E' . ($event->sheet->getHighestRow()), 'Noted by: ');


                $event->sheet->setCellValue('B' . ($event->sheet->getHighestRow() + 3), '');
                $event->sheet->setCellValue('E' . ($event->sheet->getHighestRow()), '');
                $event->sheet->getStyle('B' . ($event->sheet->getHighestRow()))->applyFromArray(['font' => [
                    'bold' => true,
                ]]);
                $event->sheet->getStyle('E' . ($event->sheet->getHighestRow()))->applyFromArray(['font' => [
                    'bold' => true,
                ]]);

                $event->sheet->setCellValue('B' . ($event->sheet->getHighestRow() + 1), 'Campus OSA Coordinator');
                $event->sheet->setCellValue('E' . ($event->sheet->getHighestRow()), 'Campus Coordinator');
            }
        ];
    }
}
