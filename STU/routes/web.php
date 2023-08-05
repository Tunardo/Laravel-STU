<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamMarkController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/student', [StudentController::class,"index"])->name('students.index');
Route::get('/student/create', [StudentController::class,"create"])->name('students.create');
Route::post('/student/store', [StudentController::class,"store"])->name('students.store');
Route::get('/student/{id}/edit', [StudentController::class,"edit"])->name('students.edit');
Route::post('/student/{id}/update', [StudentController::class,"update"])->name('students.update');
Route::delete('/students/destroy/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

Route::get('/course', [CourseController::class,"index"])->name('courses.index');
Route::get('/course/create', [CourseController::class,"create"])->name('courses.create');
Route::post('/course/store', [CourseController::class,"store"])->name('courses.store');
Route::get('/course/{id}/edit', [CourseController::class,"edit"])->name('courses.edit');
Route::post('/course/{id}/update', [CourseController::class,"update"])->name('courses.update');
Route::delete('/course/destroy/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

Route::get('/exam', [ExamMarkController::class, 'index'])->name('exam.index');
Route::get('/exam/create', [ExamMarkController::class, 'create'])->name('exam.create');
Route::post('/exam/store', [ExamMarkController::class,"store"])->name('exam.store');
Route::get('/exam/{id}/edit', [ExamMarkController::class, 'edit'])->name('exam.edit');
Route::post('/validateStudent', [ExamMarkController::class, 'validateStudent'])->name('validateStudent');
Route::get('/exam/show', [ExamMarkController::class, 'showExamMarks'])->name('exam.showExamMarks');
Route::delete('/exam/destroy/{id}', [ExamMarkController::class, 'destroy'])->name('exam.destroy');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/getAverageMarkForStudent/{studentId}', [ReportController::class, 'getAverageMarkForStudent'])->name('getAverageMarkForStudent');
Route::get('/getAverageMarkForCourse/{courseId}', [ReportController::class, 'getAverageMarkForCourse'])->name('getAverageMarkForCourse');
Route::get('/export/student/{studentId}', [ReportController::class, 'exportStudentReport'])->name('export.studentReport');
Route::get('/export/subject/{courseId}', [ReportController::class, 'exportSubjectReport'])->name('export.subjectReport');
// Route::get('/students', 'App\Http\Controllers\StudentController@index');
