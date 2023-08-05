@extends('layouts.app')

@section('title', 'Course List')

@section('content')

@if (session('success') || session('error'))
    <div class="alert alert-dismissible
        @if (session('success'))
            alert-success
        @elseif (session('error'))
            alert-danger
        @endif
    ">
        {{ session('success') ?? session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h1 class="mb-2">Courses List</h1>

<div class="col text-end">
    <a href="{{ route('courses.create') }}" class="btn btn-success">Create</a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->description }}</td>
                    <td>
                        <div class="d-flex">
                            <form action="{{ route('courses.edit', ['id' => $course->id]) }}" method="GET">
                                <button type="submit" class="btn btn-secondary me-2"><i class="bi bi-pen-fill"></i></button>
                            </form>
                            <!-- <a href="{{ route('courses.edit', ['id' => $course->id]) }}" class="btn btn-secondary me-2"><i class="bi bi-pen-fill"></i></a> -->
                            
                            <form action="{{ route('courses.destroy', ['id' => $course->id]) }}" method="POST">
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

    {{ $courses->links() }}
</div>
@endsection