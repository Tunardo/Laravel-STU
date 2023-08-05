@extends('layouts.app')

@section('title', 'Student List')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h1 class="mb-2">Students List</h1>

<div class="col text-end">
    <a href="{{ route('students.create') }}" class="btn btn-success">Create</a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Course Enrolled</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->first_name }}</td>
                    <td>{{ $student->last_name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->date_of_birth }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->address }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>
                        @foreach ($student->courses as $course)
                            {{ $course->name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>
                        <div class="d-flex">
                            <form action="{{ route('students.edit', ['id' => $student->id]) }}" method="GET">
                                <button type="submit" class="btn btn-secondary me-2"><i class="bi bi-pen-fill"></i></button>
                            </form>
                            <!-- <a href="{{ route('students.edit', ['id' => $student->id]) }}" class="btn btn-secondary me-2"><i class="bi bi-pen-fill"></i></a> -->
                            
                            <form action="{{ route('students.destroy', ['id' => $student->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $students->links() }}
@endsection