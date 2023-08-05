<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Course;
use App\Models\Student;
use App\Models\ExamMark;

class StudentReportExport implements FromCollection, WithHeadings
{
    protected $studentId;

    public function __construct($studentId)
    {
        $this->studentId = $studentId;
    }

    public function collection()
    {
        $student = Student::findOrFail($this->studentId);
        $examMarks = ExamMark::where('student_id', $this->studentId)->get();
        
        $data = [];
        $totalMarks = 0;
        
        foreach ($examMarks as $examMark) {
            $course = Course::find($examMark->course_id);

            if ($course) {
                $data[] = [
                    'Course' => $course->name,
                    'Mark' => $examMark->marks,
                ];

                $totalMarks += $examMark->marks;
            }
        }

        $averageMark = count($data) > 0 ? $totalMarks / count($data) : 0;

        $data[] = [
            'Course' => 'Average Mark:',
            'Mark' => $averageMark,
        ];

        $data[] = [
            'Course' => 'Student:',
            'Mark' => $student->first_name . ' ' . $student->last_name,
        ];

        return collect($data);
    }

    public function headings(): array
    {
        // Add column headings to the CSV file
        return [
            'Course',
            'Mark',
        ];
    }
}