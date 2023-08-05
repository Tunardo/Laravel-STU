<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Course;
use App\Models\ExamMark;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentReportExport;
use App\Exports\SubjectReportExport;

class ReportController extends Controller
{
    public function index()
    {
        $students = Student::all();
        $courses = Course::all();

        return view('reports.index', [
            'students' => $students,
            'courses' => $courses,
        ]);
    }

    public function getAverageMarkForStudent($studentId)
    {
        $examMarks = ExamMark::where('student_id', $studentId)->with('course')->get();

        $results = [];
        $totalMarks = 0;
        $totalCourses = count($examMarks);

        foreach ($examMarks as $examMark) {
            $totalMarks += $examMark->marks;

            $results[] = [
                'course_name' => $examMark->course->name,
                'marks' => $examMark->marks,
            ];
        }

        $averageMark = $totalCourses > 0 ? $totalMarks / $totalCourses : 0;

        $formattedAverageMark = number_format($averageMark, 2);

        return response()->json([
            'averageMark' => $formattedAverageMark,
            'results' => $results,
        ]);
    }

    public function getAverageMarkForCourse($courseId)
    {
        $averageMarksByCourse = ExamMark::select('course_id', DB::raw('AVG(marks) as average_mark'))
        ->groupBy('course_id')
        ->get();

        $courseNames = Course::whereIn('id', $averageMarksByCourse->pluck('course_id'))->pluck('name', 'id');

        $results = [];
        foreach ($averageMarksByCourse as $averageMark) {
            $courseId = $averageMark->course_id;
            $courseName = $courseNames[$courseId];
            $averageMarkValue = number_format($averageMark->average_mark, 2, '.', ''); // Remove decimal places

            $results[] = [
                'course_id' => $courseId,
                'course_name' => $courseName,
                'average_mark' => $averageMarkValue,
            ];
        }

        

        return response()->json([
            'results' => $results,
        ]);
    }

    public function exportStudentReport($studentId)
    {
        return Excel::download(new StudentReportExport($studentId), 'student_report.csv');
    }

    // Method to export subject report
    public function exportSubjectReport($courseId)
    {
        return Excel::download(new SubjectReportExport($courseId), 'subject_report.csv');
    }
}
