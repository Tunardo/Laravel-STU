@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
    <div class="d-flex align-items-center">
        <div class="text-start">
            <a href="{{ route('students.index') }}" class="fs-2"><i class="bi bi-caret-left-fill"></i></a>
        </div>

        <h1 class="my-2">Edit Student</h1>
    </div>

    <div class="card shadow border-0 bg-white p-4">
        <form class="card-body" method="post" action="{{ route('students.update', ['id' => $student->id]) }}">
            @csrf
            <div class="row mb-2">
                <div class="col">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ $student->first_name }}">
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ $student->last_name }}">
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ $student->email }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ $student->date_of_birth }}">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="Male" @if ($student->gender === 'Male') selected @endif>Male</option>
                        <option value="Female" @if ($student->gender === 'Female') selected @endif>Female</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ $student->address }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ $student->phone }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label for="courses" class="form-label">Enrolled Courses</label>
                    <select name="courses[]" id="courses" class="form-select" multiple>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (in_array($course->id, $student->courses->pluck('id')->toArray())) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Edit</button>

        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#courses').select2({
                theme: "bootstrap-5",
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                placeholder: $( this ).data( 'placeholder' ),
                closeOnSelect: false,
            });
        });
    </script>
@endsection
