<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Course;
use App\Models\Student;
use App\Models\ExamMark;

class SubjectReportExport implements FromCollection, WithHeadings
{
    protected $courseId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }
    public function collection()
    {
        $course = Course::findOrFail($this->courseId);
        $examMarks = ExamMark::where('course_id', $this->courseId)->get();

        $data = [];
        $totalMarks = 0;

        foreach ($examMarks as $examMark) {
            $student = Student::find($examMark->student_id);

            if ($student) {
                $data[] = [
                    'Student Name' => $student->first_name . ' ' . $student->last_name,
                    'Mark' => $examMark->marks,
                ];

                $totalMarks += $examMark->marks;
            }
        }

        $averageMark = count($data) > 0 ? $totalMarks / count($data) : 0;

        $data[] = [
            'Student Name' => 'Average Mark:',
            'Mark' => $averageMark,
        ];

        $data[] = [
            'Student Name' => 'Course:',
            'Mark' => $course->name,
        ];

        return collect($data);
    }

    public function headings(): array
    {
        // Add column headings to the CSV file with bold styling
        return [
            'Student Name',
            'Mark',
        ];
    }
}

