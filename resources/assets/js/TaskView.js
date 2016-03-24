
// load all javascript once the document is ready.
$(document).ready(function(){

    String.prototype.isEmpty = function() {
        return (this.length === 0 || !this.trim());
    };

    TaskType();
    isValidHourMinute();
    getStartTime();
    getEndTime();
    loseFocusOnStartTime();
    loseFocusOnEndTime();
    loseFocusOnType();
    enabledDisabledSaveButton();
    onClickOnSaveButton();
    causeTheTopLineOfTableHeaderToFade();

});

// convert rgb into hex for ez'er comparisons.
function rgb2hex(rgb){
    rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
    return "#" +
        ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
}

// SaveButton class to save state of required input fields.
function SaveButton(type, startt, endt) {
    this.type = type;
    this.startt = startt;
    this.endt = endt;
}
SaveButton.prototype.getType = function() {
    return this.type;
};
SaveButton.prototype.setType = function(type) {
    this.type = type;
};
SaveButton.prototype.getStartt = function() {
    return this.startt;
};
SaveButton.prototype.setStartt = function(startt) {
    this.startt = startt;
};
SaveButton.prototype.getEndt = function() {
    return this.endt;
};
SaveButton.prototype.setEndt = function(endt) {
    this.endt = endt;
};
SaveButton.prototype.isReady = function() {
    if (this.type && this.startt && this.endt) {
        return true;
    }

    return false;
};

var saveButton = new SaveButton(false,false,false);

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

// time validation are performed via http://momentjs.com/
function isValidHourMinute(ttime) {

    var myDateTime = "1960-10-03 " + ttime;
    var formats = ["YYYY-MM-DD LT","YYYY-MM-DD h:mm:ss A","YYYY-MM-DD HH:mm:ss","YYYY-MM-DD HH:mm"];

    if (moment(myDateTime, formats, true).isValid()) {
        return true;
    }

    return false;

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
    $('#endt-search').timepicker({'show2400' : true,
                           'timeFormat': 'H:i',
                           'scrollDefault': 'now',
                           'useSelect' : false });
}

// calculate hours worked and populate the hours worked cell.
function loseFocusOnEndTime() {
    $("#endt-search").focusout(function(){
        var t1Str = $('#startt-search').text($(this)).val();
        var t1 = t1Str.split(':');

        var t2Str = $('#endt-search').text($(this)).val();
        if (!isValidHourMinute(t2Str) && !t2Str.isEmpty()) {
            $('#endt-search').css('background-color', 'pink');
            saveButton.setEndt(false);
            enabledDisabledSaveButton();
            $('#hoursWorked').val("");

            return;
        }
        else {
            $('#endt-search').css('background-color', 'white');
            saveButton.setEndt(true);
            enabledDisabledSaveButton();
        }
        var t2 = t2Str.split(':');

        if (!t2Str.isEmpty() && !checkForEndTimeOverlaps()) {
            saveButton.setEndt(false);
            enabledDisabledSaveButton();
            $('#hoursWorked').val("");

            return;
        }

        if (!t1Str.isEmpty() && !t2Str.isEmpty()) {
            var beginningTime = moment({H: t1[0], s: t1[1]});
            var endTime = moment({H: t2[0], s: t2[1]});
            if (!beginningTime.isBefore(endTime)) {
                $('#endt-search').css('background-color', 'pink');
                saveButton.setEndt(false);
                enabledDisabledSaveButton();
                $('#hoursWorked').val("");

                return;
            }
            else {
                $('#endt-search').css('background-color', 'white');
                saveButton.setEndt(true);
                enabledDisabledSaveButton();
            }

            if (rgb2hex($('#startt-search').css('background-color')) === rgb2hex($('#endt-search').css('background-color')) &&
                rgb2hex($('#endt-search').css('background-color')) === "#ffffff") {
                var t1Min = Math.floor(t1[0]) *60 + Math.floor(t1[1]);
                var t2Min = Math.floor(t2[0]) *60 + Math.floor(t2[1]);

                var diff = (t2Min - t1Min)/60;

                $('#hoursWorked').val(Math.round(diff * 10000 )/10000);
            }
        }
    });
}

function loseFocusOnStartTime() {
    $("#startt-search").focusout(function(){
        var t1Str = $('#startt-search').text($(this)).val();
        if (!isValidHourMinute(t1Str) && !t1Str.isEmpty()) {
            $('#startt-search').css('background-color', 'pink');
            saveButton.setStartt(false);
            enabledDisabledSaveButton();
            $('#hoursWorked').val("");

            return;
        }
        else {
            $('#endt-search').css('background-color', 'white');
            $('#startt-search').css('background-color', 'white');
            saveButton.setStartt(true);
            enabledDisabledSaveButton();
        }
        var t1 = t1Str.split(':');

        if (!checkForStartTimeOverlaps()) {
            saveButton.setStartt(false);
            enabledDisabledSaveButton();
            $('#hoursWorked').val("");

            return;
        }

        var t2Str = $('#endt-search').text($(this)).val();
        var t2 = t2Str.split(':');

        if (t1Str.isEmpty() && t2Str.isEmpty()) {
            clearTaskTable();
        }

        if (!t1Str.isEmpty() && !t2Str.isEmpty()) {
            var beginningTime = moment({H: t1[0], s: t1[1]});
            var endTime = moment({H: t2[0], s: t2[1]});
            if (!beginningTime.isBefore(endTime)) {
                $('#startt-search').css('background-color', 'pink');
                saveButton.setStartt(false);
                enabledDisabledSaveButton();
                $('#hoursWorked').val("");

                return;
            }
            else {
                $('#startt-search').css('background-color', 'white');
                saveButton.setStartt(true);
                enabledDisabledSaveButton();
            }

            if (rgb2hex($('#startt-search').css('background-color')) === rgb2hex($('#endt-search').css('background-color')) &&
                rgb2hex($('#endt-search').css('background-color')) === "#ffffff") {
                var t1Min = Math.floor(t1[0]) *60 + Math.floor(t1[1]);
                var t2Min = Math.floor(t2[0]) *60 + Math.floor(t2[1]);

                var diff = (t2Min - t1Min)/60;

                $('#hoursWorked').val(Math.round(diff * 10000 )/10000);
            }
        }
    });
}

function loseFocusOnType() {
    $("#taskType").change(function () {
        var v1 = Math.floor($('#taskType').val());

        if (v1 === 0) {
            saveButton.setType(false);
            enabledDisabledSaveButton();
            $("#taskType").empty();
            $('#taskType').append("<option value='0'>--Select Type--</option>");
            TaskType();
        } else {
            saveButton.setType(true);
            enabledDisabledSaveButton();
        }
    });
}

// special case trap for two form events:
// - clicking the save button via jquery.
// - hitting the return key when enough info has been filled out by the user to send to the server via javascript.

// override for events for click and key press.
// this is to allow for the user to be able to hit the return key, and if the form has the data it needs it will
// create the record.  In essence, a time saver for the user.
//
// Note: the #hourWorked ID on the form has been set to disabled via the html.  In order to pass the value to the
//       server the #hoursWored ID needs to be set to disabled = false.  Once the refresh happens by the http request
//       response cycle, the #hourWorked ID will be set back to disabled = true.
//
function onClickOnSaveButton() {

    $('#saveButton').click(function() {
        $("#hoursWorked").prop('disabled', false);
    });

    $(document).keypress(function(e) {
        forKeyPress(e);
    });

    // the return key pressed, check to see if enough info has been populated to send to the server.
    var forKeyPress = function(e) {
        var startT = $('#startt-search').text($(this)).val();
        var endT = $('#endt-search').text($(this)).val();
        var hoursWorked = $('#hoursWorked').val();

        // return key pressed (e.which == 13) and the start and end times have a value, but hours worked has NOT been calculated.
        if (e.which == 13 && startT !== "" && endT !== "" && hoursWorked === "") {
            var t1 = startT.split(':');
            var t2 = endT.split(':');

            var t1Min = Math.floor(t1[0]) *60 + Math.floor(t1[1]);
            var t2Min = Math.floor(t2[0]) *60 + Math.floor(t2[1]);

            var diff = (t2Min - t1Min)/60;

            $("#hoursWorked").css('background-color', '#eee');
            $("#hoursWorked").prop('disabled', false);
            $('#hoursWorked').val(Math.round(diff * 10000 )/10000);
            $("#saveButton").click();
        } else {
            // return key pressed (e.which == 13) and the start, end, and hoursWorked have values.
            if (e.which == 13 && saveButton.isReady() && hoursWorked !== "") {
                $("#hoursWorked").css('background-color', '#eee');
                $("#hoursWorked").prop('disabled', false);
                $('#hoursWorked').val(hoursWorked);
                $("#saveButton").click();
            }
        }

    };
}

function enabledDisabledSaveButton() {
    if (saveButton.isReady()) {
        $("#saveButton").prop('disabled', false);
    } else {
        $("#saveButton").prop('disabled', true);
    }
}

function checkForStartTimeOverlaps() {

    var table = document.getElementById("taskTable");
    for (var i = 0, row; row = table.rows[i]; i++) {
        var t1Str = $('#startt-search').text($(this)).val();
        var t1 = t1Str.split(':');
        var timeToCheck = moment({H: t1[0], m: t1[1]});

        var t2Str = row.cells[1].innerHTML;
        var t2 = t2Str.split(':');
        var cellStartTime = moment({H: t2[0], m: t2[1]});

        var t2Str = row.cells[2].innerHTML;
        var t2 = t2Str.split(':');
        var cellEndTime = moment({H: t2[0], m: t2[1]});
        var cellEndTimeLess1Second = moment({H: t2[0], m: t2[1]}).subtract(1, 'seconds');

        if (!timeToCheck.isBefore(cellStartTime) && !timeToCheck.isAfter(cellEndTime)) {
            if (!timeToCheck.isBefore(cellStartTime) && !timeToCheck.isAfter(cellEndTimeLess1Second)) {
                $('#startt-search').css('background-color', 'pink');
                row.cells[1].style.color = "pink";
                row.cells[1].style.fontWeight = 'bold';
                row.cells[2].style.color = "pink";
                row.cells[2].style.fontWeight = 'bold';

                return false;
            } else {
                $('#startt-search').css('background-color', 'white');
                row.cells[1].style.color = "black";
                row.cells[1].style.fontWeight = 'normal';
                row.cells[2].style.color = "black";
                row.cells[2].style.fontWeight = 'normal';
            }
        }
    }

    saveButton.setStartt(true);
    enabledDisabledSaveButton();

    return true;
}

function checkForEndTimeOverlaps() {

    var table = document.getElementById("taskTable");
    for (var i = 0, row; row = table.rows[i]; i++) {
        var t1Str = $('#endt-search').text($(this)).val();
        var t1 = t1Str.split(':');
        var timeToCheck = moment({H: t1[0], m: t1[1]});
        var timeToCheckLess1Second = moment({H: t1[0], m: t1[1]}).subtract(1, 'seconds');


        var t2Str = row.cells[1].innerHTML;
        var t2 = t2Str.split(':');
        var cellStartTime = moment({H: t2[0], m: t2[1]});

        var t2Str = row.cells[2].innerHTML;
        var t2 = t2Str.split(':');
        var cellEndTime = moment({H: t2[0], m: t2[1]});

        if (!timeToCheck.isBefore(cellStartTime) && !timeToCheck.isAfter(cellEndTime)) {
            if (!timeToCheckLess1Second.isBefore(cellStartTime) && !timeToCheckLess1Second.isAfter(cellEndTime)) {
                $('#endt-search').css('background-color', 'pink');
                row.cells[1].style.color = "pink";
                row.cells[1].style.fontWeight = 'bold';
                row.cells[2].style.color = "pink";
                row.cells[2].style.fontWeight = 'bold';

                return false;
            } else {
                $('#endt-search').css('background-color', 'white');
                row.cells[1].style.color = "black";
                row.cells[1].style.fontWeight = 'normal';
                row.cells[2].style.color = "black";
                row.cells[2].style.fontWeight = 'normal';
            }
        }
    }

    saveButton.setEndt(true);
    enabledDisabledSaveButton();

    return true;
}

function clearTaskTable() {

    var table = document.getElementById("taskTable");
    for (var i = 0, row; row = table.rows[i]; i++) {
        row.cells[1].style.color = "black";
        row.cells[1].style.fontWeight = 'normal';
        row.cells[2].style.color = "black";
        row.cells[2].style.fontWeight = 'normal';
    }
    saveButton.setStartt(false);
    saveButton.setEndt(false);
    enabledDisabledSaveButton();

    return true;
}
