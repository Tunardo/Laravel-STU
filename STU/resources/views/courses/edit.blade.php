@extends('layouts.app')

@section('title', 'Edit Course')

@section('content')
    <div class="d-flex align-items-center">
        <div class="text-start">
            <a href="{{ route('courses.index') }}" class="fs-2"><i class="bi bi-caret-left-fill"></i></a>
        </div>

        <h1 class="my-2">Edit Course</h1>
    </div>

    <div class="card shadow border-0 bg-white p-4">
        <form class="card-body" method="post" action="{{ route('courses.update', ['id' => $course->id]) }}">
            @csrf
            <div class="row mb-2">
                <div class="col">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ $course->name }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ $course->description }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Edit</button>
        </form>
    </div>
@endsection