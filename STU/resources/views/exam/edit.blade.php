@extends('layouts.app')

@section('title', 'Edit Marks')

@section('content')
    <div class="d-flex align-items-center">
        <div class="text-start">
            <a href="{{ route('exam.index') }}" class="fs-2"><i class="bi bi-caret-left-fill"></i></a>
        </div>

        <h1 class="my-2">Edit Marks</h1>
    </div>

    <div class="card shadow border-0 bg-white p-4">
        <div id="studentDetails">
            <h2>Student Details</h2>
            <p><strong>ID:</strong> {{ $student->id }} </p>
            <p><strong>Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
            <p><strong>Email:</strong> {{ $student->email }}</p>
            <p><strong>Phone:</strong> {{ $student->phone }}</p>
        </div>
    </div>

@endsection