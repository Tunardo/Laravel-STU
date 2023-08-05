<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::orderBy('id', 'desc')->paginate(10);

        return view('courses.list')->with('courses', $courses);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'The course name is required.',
            'name.max' => 'The course name must not exceed :max characters.',
        ]);

        try {
            Course::create($validatedData);
        } catch (\Exception $e) {
            // Handle any unexpected exceptions here, if needed
            return redirect(route('courses.index'))->with('error', 'An error occurred while saving the course.' . $e->getMessage());
        }

        return redirect(route('courses.index'))->with('success', 'Course created successfully!');
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
        $course = Course::find($id);
        
        if(!$course){
            return redirect(route('courses.index'))->with('error', 'Student not found.');
        }

        return view('courses.edit')->with('course', $course);
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
        $course = Course::find($id);

        if(!$course){
            return redirect(route('courses.index'))->with('error', 'Course not found.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'The course name is required.',
            'name.max' => 'The course name must not exceed :max characters.',
        ]);

        try {
            $course->update($validatedData);
        } catch (\Exception $e) {
            // Handle any unexpected exceptions here, if needed
            return redirect(route('courses.index'))->with('error', 'An error occurred while updating the course.');
        }

        return redirect(route('courses.index'))->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return redirect(route('courses.index'))->with('error', 'Course not found.');
        }

        try {
            $course->delete();
        } catch (\Exception $e) {
            return redirect(route('courses.index'))->with('error', 'An error occurred while deleting the course.');
        }

        return redirect(route('courses.index'))->with('success', 'Course deleted successfully!');
    }
}
