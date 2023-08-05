<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ExamMark;
use App\Models\Student;
use App\Models\Course;

class ExamMarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();
        $examMarks = ExamMark::with('student', 'course')->orderBy('id', 'desc')->get();

        return view('exam.list')->with(['examMarks' => $examMarks, 'courses' => $courses]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('exam.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'marks' => 'required|integer|min:0|max:100',
        ], [
            'student_id.required' => 'The student ID field is required.',
            'student_id.exists' => 'Student not found. Please enter a valid student ID.',
            'course_id.required' => 'The course field is required.',
            'course_id.exists' => 'Course not found. Please choose a valid course.',
            'marks.required' => 'The marks field is required.',
            'marks.integer' => 'The marks must be an integer.',
            'marks.min' => 'The marks must be at least 0.',
            'marks.max' => 'The marks must not exceed 100.',
        ]);
    
        if ($validator->fails()) {
            return redirect(route('exam.create'))->withErrors($validator)->withInput();
        }

        try{
            // ExamMark::updateOrCreate($validator->validated());
            $examMark = ExamMark::updateOrCreate(
                ['student_id' => $request->input('student_id'), 'course_id' => $request->input('course_id')],
                ['marks' => $request->input('marks')]
            );

            if ($examMark->wasRecentlyCreated) {
                return redirect(route('exam.index'))->with('success', 'Exam mark added successfully!');
            } else {
                return redirect(route('exam.index'))->with('success', 'Exam mark updated successfully!');
            }
            
        } catch (\Exception $e) {
            // Handle any unexpected exceptions here, if needed
            return redirect(route('exam.index'))->withErrors('error', 'An error occurred while saving the mark.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $examMark = ExamMark::find($id);

        // if (!$examMark) {
        //     return redirect(route('exam.index'))->withErrors('error', 'Exam mark not found.');
        // }

        // $student = $examMark->student;
        // $course = $examMark->course;

        // return view('exam.edit')->with([
        //     'examMark' => $examMark,
        //     'student' => $student,
        //     'course' => $course,
        // ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $examMark = ExamMark::find($id);

        if (!$examMark) {
            return redirect(route('exam.index'))->with('error', 'Exam mark not found.');
        }

        try {
            $examMark->delete();
        } catch (\Exception $e) {
            return redirect(route('exam.index'))->with('error', 'An error occurred while deleting the mark.');
        }

        return redirect(route('exam.index'))->with('success', 'Mark deleted successfully!');
    }

    public function validateStudent(Request $request)
    {
        $studentId = $request->input('id');

        if (!$studentId) {
            return response()->json([
                'error' => 'Please enter a student ID.',
                'isValid' => false,
            ]);
        }
    
        $student = Student::with('courses')->find($studentId);
    
        if (!$student) {
            return response()->json([
                'error' => 'Student not found. Please enter a valid student ID.',
                'isValid' => false,
            ]);
        }

        $examMarks = ExamMark::where('student_id', $studentId)->get(['course_id', 'marks']);
    
        return response()->json([
            'student' => $student,
            'examMarks' => $examMarks,
            'isValid' => true,
        ]);
    }

    public function showExamMarks(Request $request)
    {
        $courseId = $request->query('course_id');
        $courses = Course::all();

        if ($courseId === 'all') {
            $examMarks = ExamMark::with('student', 'course')->get();
            $selectedCourse = null; // No selected course when "All" is chosen
        }else{
            $selectedCourse = Course::find($courseId);
            if (!$selectedCourse) {
                return redirect()->route('exam.index')->with('error', 'Please select a valid course.');
            }

            $examMarks = ExamMark::where('course_id', $courseId)->with('student')->get();
        }
        
        return response()->json([
            'selectedCourse' => $selectedCourse,
            'courses' => $courses,
            'examMarks' => $examMarks,
        ]);

        // return view('exam.list')->with([
        //     'selectedCourse' => $selectedCourse,
        //     'courses' => $courses,
        //     'examMarks' => $examMarks,
        // ]);
    }
}
