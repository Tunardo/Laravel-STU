@extends('layouts.app')

@section('title', 'Reports')

@section('content')

<h1 class="mb-2">Report</h1>

<div class="card shadow border-0 bg-white pt-4 px-4">
    <div class="card-body">
        <div class="row mb-2">
            <label for="" class="form-label">Choose to show average mark for:</label>
            <div class="col">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="student">
                    <label class="form-check-label" for="inlineRadio1">Student</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="subject">
                    <label class="form-check-label" for="inlineRadio2">Subject</label>
                </div>
            </div>
        </div>

        <div class="row mb-2" id="studentSelectDiv" style="display:none;">
            <div class="col">
                <label for="student" class="form-label">Select a student</label>
                <select id="studentSelect" class="form-select" name="student_id">
                    <option value="">Choose...</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->id }})</option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="errorSelectStudent"></div>
            </div>
        </div>

        <div class="row mb-2" id="subjectSelectDiv" style="display:none;">
            <div class="col">
                <label for="course" class="form-label">Select a course</label>
                <select id="courseSelect" class="form-select" name="course_id">
                    <option value="">Choose...</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="errorSelectCourse"></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow border-0 bg-white mt-2 px-4" id="reportRes" style="display:none;">
    <div class="card-body">
        <h2></h2>

        <div id="resultsDiv" style="display:none;"></div>

        <div id="avgResult" style="display:none;">
            <strong>Average Mark:</strong>
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" id="progressBar">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 1%;"></div>
            </div>
        </div>

        <div id="avgCourse" style="display:none;">
            <strong>Average Mark:</strong>
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" id="courseBar">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 1%;"></div>
            </div>
        </div>

        <a href="#" id="exportReport" style="display:none;" class="btn btn-success mt-2">Export CSV</a>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('input[name="inlineRadioOptions"]').on('change', function() {
            var selectedOption = $(this).val();

            $('#reportRes').fadeIn();

            if (selectedOption === 'student') {
                $('#studentSelectDiv').fadeIn();
                $('#subjectSelectDiv').hide();

                // Clear the contents of the result and average divs
                $('#resultsDiv').fadeIn();
                $('#avgResult').fadeIn();
                $('#avgCourse').hide()

            } else if (selectedOption === 'subject') {
                $('#studentSelectDiv').hide();
                $('#subjectSelectDiv').fadeIn();

                // Clear the contents of the result and average divs
                $('#resultsDiv').hide();
                $('#avgResult').hide();
                $('#avgCourse').fadeIn()
            } else {
                $('#studentSelectDiv').hide();
                $('#subjectSelectDiv').hide();

                // Clear the contents of the result and average divs
                $('#resultsDiv').empty().hide();
                $('#avgResult').hide();
            }

            $('#exportReport').fadeIn();
        });

        $('#exportReport').on('click', function() {
            var selectedOption = $('input[name="inlineRadioOptions"]:checked').val();

            if (selectedOption === 'student') {
                var selectedStudentId = $('#studentSelect').val();
                if (selectedStudentId) {
                    var url = "{{ route('export.studentReport', ':studentId') }}".replace(':studentId', selectedStudentId);
                    window.location.href = url;
                }
            } else if (selectedOption === 'subject') {
                var selectedCourseId = $('#courseSelect').val();
                if (selectedCourseId) {
                    var url = "{{ route('export.subjectReport', ':courseId') }}".replace(':courseId', selectedCourseId);
                    window.location.href = url;
                }
            }
        });

        $('#studentSelect').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });

        $('#courseSelect').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });

        $('#courseSelect').on('change', function() {
            var selectedCourseId = $(this).val();
            var avgCourse = $('#avgCourse');

            if (selectedCourseId) {
                var url = "{{ route('getAverageMarkForCourse', ':courseId') }}".replace(':courseId', selectedCourseId);

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        console.log(123,data)

                        data.results.forEach(function(result) {
                            if(selectedCourseId == result.course_id){
                                var averagePercentage = parseFloat(result.average_mark).toFixed(2);

                                var $averageProgressBar = $('#courseBar .progress-bar');
                                $averageProgressBar.width(averagePercentage + '%');
                                $averageProgressBar.text(parseInt(averagePercentage) + '%');

                                avgCourse.fadeIn();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                    }
                });
            }
        });

        $('#studentSelect').on('change', function() {
            var selectedStudentId = $(this).val();
            var progressBar = $('#progressBar');
            var resultsDiv = $('#resultsDiv');
            var avgResult = $('#avgResult');

            if (selectedStudentId) {
                var url = "{{ route('getAverageMarkForStudent', ':studentId') }}".replace(':studentId', selectedStudentId);

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        var averageMark = parseFloat(data.averageMark);
                        var averagePercentage = (averageMark / 100) * 100;

                        var $averageProgressBar = $('#progressBar .progress-bar');
                        $averageProgressBar.width(averagePercentage + '%');
                        $averageProgressBar.text(parseInt(averagePercentage) + '%');

                        avgResult.fadeIn();

                        var resultsContent = '';

                        data.results.forEach(function(result) {
                             var coursePercentage = (result.marks / 100) * 100;

                             var progressBarClass = 'progress-bar';

                             if (coursePercentage >= 80) {
                                progressBarClass += ' bg-success'; // Green for >= 90%
                            } else if (coursePercentage >= 40) {
                                progressBarClass += ' bg-warning'; // Yellow for >= 60%
                            } else {
                                progressBarClass += ' bg-danger';  // Red for < 60%
                            }

                            // Create the progress bar for each course
                            resultsContent += '<div>' + result.course_name + ': ';
                            resultsContent += '<div class="progress mb-2">';
                            resultsContent += '<div class="' + progressBarClass + '" role="progressbar" style="width: ' + coursePercentage + '%;" aria-valuenow="' + result.marks + '" aria-valuemin="0" aria-valuemax="100">';
                            resultsContent += result.marks + '%';
                            resultsContent += '</div>';
                            resultsContent += '</div>';
                            resultsContent += '</div>';
                        });

                        resultsDiv.html(resultsContent);
                        resultsDiv.fadeIn();

                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                    }
                });
            } else {
                // If no student is selected, hide the average mark div
                $('#averageMarkDiv').hide();
            }
        });
    });

    // function showProgressBar(e) {

    //     if (e == 'student') {
    //         $('#progressBar').fadeIn();
    //         $('#courseBar').hide();
    //     } else if (e == 'course'){
    //         $('#progressBar').hide();
    //         $('#courseBar').fadeIn();
    //     }
    // }
</script>

@endsection