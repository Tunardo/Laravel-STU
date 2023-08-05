@extends('layouts.app')

@section('title', 'Create Marks')


@section('content')

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <ul class="m-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex align-items-center">
        <div class="text-start">
            <a href="{{ route('exam.index') }}" class="fs-2"><i class="bi bi-caret-left-fill"></i></a>
        </div>

        <h1 class="my-2">Add Exam Marks</h1>
    </div>

    <div class="card shadow border-0 bg-white p-4">
        <form class="card=body" id="validateStudentForm">
            @csrf
            <div class="row mb-2">
                <label for="student_id" class="form-label">Enter student ID</label>
                <div class="col">
                    <input type="text" name="student_id" id="student_id" class="form-control">
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback" id="errorText"></div>
                </div>

                <div class="col">
                    <button type="submit" class="btn btn-secondary">Search</button>
                </div>
            </div>

            <div id="studentDetails" style="display: none;">
                <h2>Student Details</h2>
                <p><strong>ID:</strong> <span id="studentID"></span></p>
                <p><strong>Name:</strong> <span id="studentName"></span></p>
                <p><strong>Email:</strong> <span id="studentEmail"></span></p>
                <p><strong>Phone:</strong> <span id="studentPhone"></span></p>
            </div>
        </form>
    </div>

    <div class="card shadow border-0 bg-white p-4 mt-2" id="markDetails" style="display: none;">
        <form method="post" action="{{ route('exam.store') }}">
            @csrf
            <input type="text" name="student_id" id="getStudent" value="" hidden>

            <div class="row mb-2">
                <div class="col">
                    <label for="course" class="form-label">Choose the subject</label>
                    <select id="courseSelect" class="form-select" name="course_id">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback" id="errorSelect"></div>
                </div>
                
            </div>

            <div class="row mb-2">
                <label for="mark" class="form-label">Enter the mark</label>
                <div class="col-6">
                    <input type="range" class="form-range" id="markRange" min="0" max="100" step="1">
                </div>

                <div class="col">
                    <input type="text" class="form-control" name="marks" id="markInput" min="0" max="100" step="1">
                </div>
            </div>
            
            <button type="submit" id="doneBtn" class="btn btn-success">Done</button>
        </form>
    </div>

    <script>
        $('#validateStudentForm').submit(function(event) {
            event.preventDefault();
            const studentId = $('#student_id').val();

            // Make the Ajax request using jQuery
            $.ajax({
                url: '/validateStudent',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: JSON.stringify({
                    id: studentId
                }),
                dataType: 'json',
                success: function(data) {
                    console.log(123,data)
                    $("#student_id").removeClass("is-invalid");
                    $("#studentName").text('');
                    $("#studentEmail").text('');
                    $("#studentPhone").text('');

                    if (data.isValid) {
                        student = data.student;
                        // console.log(data.student);
                        $("#studentID").text(student.id);
                        $("#studentName").text(student.first_name + ' ' + student.last_name);
                        $("#studentEmail").text(student.email);
                        $("#studentPhone").text(student.phone);

                        $("#student_id").addClass("is-valid");

                        $("#studentDetails").fadeIn();

                        const courseSelect = $('#courseSelect');
                        courseSelect.empty();
                        $("#courseSelect").removeClass("is-invalid");
                        $("#doneBtn").removeClass("disabled");

                        if(student.courses.length > 0){
                            student.courses.forEach(function (course) {
                                const option = $('<option>', {
                                    value: course.id,
                                    text: course.name
                                });
                                courseSelect.append(option);
                            });

                            // $("#markInput").val(studentId);
                            $("#getStudent").val(studentId);

                        }else{
                            $("#courseSelect").addClass("is-invalid");
                            $("#errorSelect").text('The student is not enrolled any course');

                            $("#doneBtn").addClass("disabled");
                        }

                        $("#markDetails").fadeIn();
                    }else{
                        $("#student_id").addClass("is-invalid");
                        $("#errorText").text(data.error);

                        $("#studentDetails").hide();
                        $("#markDetails").hide();
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        $(document).ready(function () {
            const rangeInput = $('#markRange');
            const textInput = $('#markInput');

            // Function to update the value of both inputs
            function updateInputs(value) {
                rangeInput.val(value);
                textInput.val(value);
            }

            // When the range input changes, update the text input
            rangeInput.on('input', function () {
                const value = $(this).val();
                updateInputs(value);
            });

            // When the text input changes, update the range input
            textInput.on('input', function () {
                const value = $(this).val();
                updateInputs(value);
            });

            $('#courseSelect').select2({
                theme: "bootstrap-5",
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                placeholder: $( this ).data( 'placeholder' ),
            });
        });
    </script>
@endsection