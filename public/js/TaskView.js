
// load all javascript once the document is ready.
$(document).ready(function(){
    // populate the type drop-down box on the Task View.
    TaskType();
    isHourValid();
    isMinuteValid();
    getStartTime();
    getEndTime();
    calcHoursWorked();
});


// populate the type drop-down box on the Task View.
function TaskType() {
    //$('#taskType').empty();
    //$('#taskType').append("<caption>Loading...</caption>");
    $.ajax({
        type: "GET",
        url: "/get_all_tasks",
        contentType: "application/json; charset=utf8",
        dataType: "json",
        success: function(data) {
            //$('#taskType').empty();
            //$('#taskType').append("<option value='0'>--Select Type--</option>");
            $.each(data,function(i,item) {
                $('#taskType').append("<option value=" + data[i].id + ">" + data[i].type + "</option>");
            });
        },
        complete: function() {
        }
    });
}

// determine hour is valid
function isHourValid(hours) {
    var t1 = hours.split(':');

    if (Math.floor(t1[0]) < 0 || Math.floor(t1[1]) > 24) {
        return false;
    }

    return true;
}

// determine hour is valid
function isMinuteValid(minutes) {
    var t1 = minutes.split(':');

    if (Math.floor(t1[0]) < 0 || Math.floor(t1[1]) > 60) {
        return false;
    }

    return true;
}

// populate start and end time boxes.
// for more details, see https://github.com/jonthornton/jquery-timepicker#timepicker-plugin-for-jquery
function getStartTime() {
    $('#startt-search').timepicker({'show2400' : true,
                             'timeFormat': 'H:i',
                             'scrollDefault': 'now',
                             'useSelect' : false });
}
function getEndTime() {
    $('#endt').timepicker({'show2400' : true,
                           'timeFormat': 'H:i',
                           'scrollDefault': 'now',
                           'useSelect' : false });
}

// calculate hours worked and populate the hours worked cell.
function calcHoursWorked() {
    $("#endt").focusout(function(){

        var t1Str = $('#startt-search').text($(this)).val();
        var t1 = t1Str.split(':');

        var t2Str = $('#endt').text($(this)).val();
        var t2 = t2Str.split(':');

        var t1Min = Math.floor(t1[0]*60) + Math.floor(t1[1]);
        var t2Min = Math.floor(t2[0]*60) + Math.floor(t2[1]);

        var diff = (t2Min - t1Min)/60;

        $('#hoursWorked').val(diff);
    });
}


//# sourceMappingURL=TaskView.js.map
