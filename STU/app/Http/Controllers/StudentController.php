<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Contracts\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::orderBy('id', 'desc')->paginate(10);
        // dd($students);
        return view('students.list')->with('students', $students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        return view('students.create')->with('courses', $courses);
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
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:Male,Female',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ], [
            'first_name.required' => 'The first name field is required.',
            'last_name.required' => 'The last name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address is already in use.',
            'date_of_birth.required' => 'The date of birth field is required.',
            'date_of_birth.date' => 'Please enter a valid date for the date of birth.',
            'gender.required' => 'The gender field is required.',
            'gender.in' => 'Please select a valid gender (Male or Female).',
            'address.required' => 'The address field is required.',
            'phone.required' => 'The phone field is required.',
            'courses.array' => 'The courses field must be an array.',
            'courses.*.exists' => 'One or more of the selected courses are invalid.',
        ]);

        // dd($validatedData);
        try {
            $student = Student::create($validatedData);

            if ($request->has('courses')) {
                $courses = $request->input('courses');
                $student->courses()->attach($courses);
            }

        } catch (\Exception $e) {
            // Handle any unexpected exceptions here, if needed
            return redirect(route('students.index'))->with('error', 'An error occurred while saving the student.');
        }

        return redirect(route('students.index'))->with('success', 'Student created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $student = Student::with('courses')->find($id);
        // return view('students.show')->with('student', $student);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $student = Student::find($id);
        
        if(!$student){
            return redirect(route('students.index'))->with('error', 'Student not found.');
        }

        $student = Student::with('courses')->find($id);
        $courses = Course::all();
        
        return view('students.edit')->with('student', $student)->with('courses', $courses);
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
        $student = Student::find($id);

        if(!$student){
            return redirect(route('students.index'))->with('error', 'Student not found.');
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
            'required',
            'email',
            Rule::unique('students')->ignore($student->id, 'id'),
        ],
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:Male,Female',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ], [
            'first_name.required' => 'The first name field is required.',
            'last_name.required' => 'The last name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address is already in use.',
            'date_of_birth.required' => 'The date of birth field is required.',
            'date_of_birth.date' => 'Please enter a valid date for the date of birth.',
            'gender.required' => 'The gender field is required.',
            'gender.in' => 'Please select a valid gender (Male or Female).',
            'address.required' => 'The address field is required.',
            'phone.required' => 'The phone field is required.',
        ]);

        try {
            $student->update($validatedData);

            $courses = $request->input('courses', []);
            $student->courses()->sync($courses);
        } catch (\Exception $e) {
            // Handle any unexpected exceptions here, if needed
            return redirect(route('students.index'))->with('error', 'An error occurred while updating the student.');
        }

        return redirect(route('students.index'))->with('success', 'Student updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return redirect(route('students.index'))->with('error', 'Student not found.');
        }

        try {
            $student->delete();
        } catch (\Exception $e) {
            return redirect(route('students.index'))->with('error', 'An error occurred while deleting the student.');
        }

        return redirect(route('students.index'))->with('success', 'Student deleted successfully!');
    }
}
