@extends('layouts.app')

@section('title', 'Exam List')

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

<h1 class="mb-2">Exam Marks List</h1>

<div class="card shadow border-0 bg-white p-4">
    <div class="card-body">
        <form method="GET" action="{{ route('exam.showExamMarks') }}">
            @csrf
            <div class="row mb-2">
                <label for="courses" class="form-label">Select subject to view</label>
                <div class="col">
                    <select name="course_id" id="courses" class="form-select">
                        <option value="all" {{ old('course_id') == 'all' ? 'selected' : '' }}>All</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col mb-2 text-end">
                    <a href="{{ route('exam.create') }}" class="btn btn-success">Add</a>
                </div>
            </div>
        </form>
        

        <div class="table-responsive text-bg-light">
            <table class="table table-light table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Course Name</th>
                        <th>Marks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="exam-marks-table-body">
                    @if(isset($selectedCourse))
                        {{ $selectedCourse->name === 'all' ? $allExamMarksTableContent : '' }}
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#courses').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });

        function loadExamMarks(selectedCourseId) {
            var url = "{{ route('exam.showExamMarks') }}?course_id=" + selectedCourseId;

            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    console.log(123,data)
                    if (data.examMarks.length > 0) {
                        var tableContent = '';
                        $.each(data.examMarks, function(index, examMark) {
                            tableContent += '<tr>';
                            tableContent += '<td>' + examMark.id + '</td>';
                            tableContent += '<td>' + examMark.student.first_name + ' ' + examMark.student.last_name + ' (' + examMark.student.id + ')' + '</td>';
                            tableContent += '<td>' + (data.selectedCourse ? data.selectedCourse.name : examMark.course.name) + '</td>';
                            tableContent += '<td>' + examMark.marks + '</td>';
                            tableContent += '<td><form action="/exam/destroy/' + examMark.id + '" method="POST">';
                            tableContent += '@method('DELETE')';
                            tableContent += '@csrf';
                            tableContent += '<button type="submit" class="btn btn-danger"><i class="bi bi-trash-fill"></i></button></form></td>';
                            tableContent += '</tr>';
                        });

                        $('#exam-marks-table-body').fadeOut(100, function() {
                            $(this).html(tableContent).fadeIn(100);
                        });
                    } else {
                        // If no exam marks are found, display a message or handle it as per your requirement
                        $('#exam-marks-table-body').html('<tr><td colspan="5">No exam marks found for the selected course.</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                }
            });
        };

        $('#courses').on('change', function() {
            var selectedCourseId = $(this).val();
            loadExamMarks(selectedCourseId);
        });

        var selectedCourseId = $('#courses').val();
        loadExamMarks(selectedCourseId);
    });
</script>
@endsection