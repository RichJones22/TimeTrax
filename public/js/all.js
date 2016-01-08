
// load all javascript once the document is ready.
$(document).ready(function(){
    //$("body").css("display", "none");
    //$("body").fadeIn(1500);

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

function causeTheTopLineOfTableHeaderToFade() {
    var valueIs = $('#thAlertMessage').val();
    if (typeof valueIs != 'undefined') {
        (setTimeout(function () {
            document.getElementById('thAlertMessage').style.display='none';
            $('#thNoAlertMessage').fadeIn(3000);
            //document.getElementById('thAlertMessage').style.display='none';
            //document.getElementById('thNoAlertMessage').style.display='block';
            //$('#thNoAlertMessage').css('display', '');
        }, 10000))();
    }
}

//// set the width of the td for the second table to th width of the first table.
//function setTheWidthOfTheTableDetailForTheSecondTableToTableHeadingWidthOfTheFirstTable() {
//    document.getElementById("tdTypeId").style.width=$("#thTypeId").css("width");
//    document.getElementById("tdStartt").style.width=$("#thStartt").css("width");
//    document.getElementById("tdEndt").style.width=$("#thEndt").css("width");
//    document.getElementById("tdHoursWorked").style.width=$("#thHoursWorked").css("width");
//    document.getElementById("tdNotes").style.width=$("#thNotes").css("width");
//}

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

function onClickOnSaveButton() {
    $("#saveButton").click(function () {
        $("#hoursWorked").prop('disabled', false);
    });
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

    //if (!timeToCheck.isBefore(cellEndTime)) {
    //    clearTaskTable();
    //}

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

    //if (!timeToCheck.isAfter(cellStartTime)) {
    //    clearTaskTable();
    //}
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
    saveButton.setStartt(true);
    saveButton.setEndt(true);
    enabledDisabledSaveButton();

    return true;
}


// namespace.
var taskType = {};

// load all javascript once the document is ready.
$(document).ready(function(){
    taskType.loseFocusOnTaskType();
    taskType.loseFocusOnDescription();
    taskType.causeTheTopLineOfTableHeaderToFade();
});

// SaveButton class to save state of required input fields.
taskType.SaveButton = function(taskType, description) {
    this.taskType = taskType;
    this.description = description;
}
taskType.SaveButton.prototype.getType = function() {
    return this.taskType;
};
taskType.SaveButton.prototype.setType = function(taskType) {
    this.taskType = taskType;
};
taskType.SaveButton.prototype.getDescription = function() {
    return this.description;
};
taskType.SaveButton.prototype.setDescription = function(description) {
    this.description = description;
};
taskType.SaveButton.prototype.isReady = function() {
    if (this.taskType && this.description) {
        return true;
    }

    return false;
};

taskType.saveButton = new taskType.SaveButton(false,false);

function enabledDisabledSaveButton01() {
    if (taskType.saveButton.isReady()) {
        $("#saveButtonTaskType").prop('disabled', false);
    } else {
        $("#saveButtonTaskType").prop('disabled', true);
    }
}

taskType.causeTheTopLineOfTableHeaderToFade = function() {
    var valueIs = $('#thAlertMessage').val();
    if (typeof valueIs != 'undefined') {
        (setTimeout(function () {
            document.getElementById('thAlertMessage').style.display='none';
            $('#thNoAlertMessage').fadeIn(3000);
            //document.getElementById('thAlertMessage').style.display='none';
            //document.getElementById('thNoAlertMessage').style.display='block';
            //$('#thNoAlertMessage').css('display', '');
        }, 10000))();
    }
}

// rom http://www.mediacollege.com/internet/javascript/text/count-words.html
taskType.countWords = function countWords(s){
    //s = s.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
    //s = s.replace(/[ ]{2,}/gi," ");//2 or more space to 1
    //s = s.replace(/\n /,"\n"); // exclude newline with a start spacing
    return s.split(' ').length;
};

taskType.isTaskTypeADuplicate = function() {

    var table = document.getElementById("taskTypeTable");
    for (var i = 0, row; row = table.rows[i]; i++) {
        var t1Str = $('#taskType01').text($(this)).val();

        var t2Str = row.cells[0].innerHTML;

        if (t1Str.toUpperCase() === t2Str.toUpperCase()) {
            return true;
        }
    }

    return false;
};

// check for single none duplicated words.
taskType.loseFocusOnTaskType = function() {
    $("#taskType01").focusout(function(){
        var t1Str = $('#taskType01').text($(this)).val();

        // only allow one word
        if (taskType.countWords(t1Str) > 1) {
            $('#taskType01').css('background-color', 'pink');

            $('#taskTypeHeader').text("Error: Type restricted to one word.");
            $('#taskTypeHeader').css('color', 'brown');
            $('#taskTypeHeader').css('font-weight', 'bold');
            taskType.saveButton.setType(false);
            enabledDisabledSaveButton01();

            return;
        }

        // nothing entered
        if (t1Str.isEmpty()) {
            taskType.saveButton.setType(false);
            enabledDisabledSaveButton01();

            return;
        }

        if (taskType.isTaskTypeADuplicate()) {
            $('#taskType01').css('background-color', 'pink');

            $('#taskTypeHeader').text("Error: Type already exists.");
            $('#taskTypeHeader').css('color', 'brown');
            $('#taskTypeHeader').css('font-weight', 'bold');
            taskType.saveButton.setType(false);
            enabledDisabledSaveButton01();

            return;
        } else {
            $('#taskType01').css('background-color', 'white');
            document.getElementById("taskTypeHeader").style.color=$("#taskTypeId").css("color");
            $('#taskTypeHeader').text("Task Type Maintenance");
        }

        // place in correct format
        var tmp = t1Str.toLowerCase();
        tmp = tmp.charAt(0).toUpperCase() + tmp.slice(1);
        $('#taskType01').val(tmp);

        taskType.saveButton.setType(true);
        enabledDisabledSaveButton01();

    });
}

// must have a value.
taskType.loseFocusOnDescription = function() {
    $("#description").focusout(function(){
        var t1Str = $('#description').text($(this)).val();

        // nothing entered
        if (t1Str.isEmpty()) {
            taskType.saveButton.setDescription(false);
            enabledDisabledSaveButton01();

            return;
        }

        // place in correct format
        var tmp = t1Str.toLowerCase();
        tmp = tmp.charAt(0).toUpperCase() + tmp.slice(1);
        $('#description').val(tmp);

        taskType.saveButton.setDescription(true);
        enabledDisabledSaveButton01();

        // tab to save button
        $("#saveButtonTaskType").focus();

    });
}






// namespace.
var TimeCard = {};

// load all javascript once the document is ready.
$(document).ready(function(){

});

$("#timeCardCalendar").mouseover(function() {
    $(this).css('cursor', 'pointer');
});

$("#timeCardCalendar").focusin(function() {
    $(this).css({ 'color': 'darkgray'});
    $("#timeCardCalendar").datepicker({dateFormat: "yy-mm-dd",
        onSelect: function()
        {
            var myVal = new Date($(this).datepicker('getDate'));
            $("#formNext").attr("action", "http://timetrax.dev/timeCard/" + (myVal.toISOString()).substr(0,10));
            $("#formNext").submit();
        }
    });
});

// populate the work type drop-down box on the Task View.
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








//# sourceMappingURL=all.js.map
