
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
// - clicking the save button.
// - hitting the return key when enough info has been filled out by the user to send to the server.

// override for events for click and key press.
// this is to allow for the user to be able to hit the return key, and if the form has the data it needs it will
// create the record.  In essence, a time saver for the user.
//
// Note: the #hourWorked id on the form has been set to disabled via the html.  In order to pass the value to the
//       server the #hoursWored id needs to be set to disabled false.  Once the refresh happens by the http request
//       response cycle, the #hourWorked id will be set back to disabled.
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
            //else {
            //    // the default case; the save button has been clicked.
            //    $("#hoursWorked").prop('disabled', false);
            //    $("#saveButton").click();
            //}
        }

    };
//
//// override for events for click and key press.
//    $(document)
//        .click(forKeyPress)
//        .keypress(forKeyPress)
//    ;
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
    saveButton.setStartt(false);
    saveButton.setEndt(false);
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
var timeCard = {};

// load all javascript once the document is ready.
$(document).ready(function(){
    timeCard.WorkType();
    timeCard.loseFocusOnDOW();
    timeCard.loseFocusOnType();
    timeCard.doHoursExistForWorkTypeDescription();

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
            $("#formNext").attr("action", appGlobal.timeCardURI + (myVal.toISOString()).substr(0,10));
            $("#formNext").submit();
        }
    });
});

// populate the work type drop-down box on the TimeCard View.
timeCard.WorkType = function() {
    if (appGlobal.clientId) {
        $.ajax({
            type: "GET",
            url: appGlobal.workURI + appGlobal.clientId,
            contentType: "application/json; charset=utf8",
            dataType: "json",
            success: function(data) {
                $.each(data,function(i,item) {
                    $('#workType').append("<option value=" + data[i].id + ">" + data[i].type + "--" + data[i].work_type_description + "</option>");
                });
            },
            complete: function() {
            }
        });
    }
}

// SaveButton class to save state of required input fields.
timeCard.SaveButton = function(type, calledFrom, bInError) {
    this.hours = [0,0,0,0,0,0,0];
    this.isHourInError=[0,0,0,0,0,0,0];
    this.type = type;
    this.calledFromWorkType=calledFrom;
};
timeCard.SaveButton.prototype.areHoursSet = function() {
    var sum=0;
    this.hours.forEach(function(pos){sum+=pos});
    return sum;
};
timeCard.SaveButton.prototype.getHours = function() {
    return this.hours;
};
timeCard.SaveButton.prototype.setHours = function(pos, value) {
    this.hours[pos] = value ? 1 : 0;
};
timeCard.SaveButton.prototype.getType = function() {
    return this.type;
};
timeCard.SaveButton.prototype.setType = function(type) {
    this.type = type;
};
timeCard.SaveButton.prototype.getCalledFromWorkType = function() {
    return this.calledFromWorkType;
};
timeCard.SaveButton.prototype.setCalledFromWorkType = function(bool) {
    this.calledFromWorkType = bool;
};
timeCard.SaveButton.prototype.getIsHourInError = function() {
    var sum=0;
    this.isHourInError.forEach(function(pos){sum+=pos});
    return sum;
};
timeCard.SaveButton.prototype.setIsHourInError = function(pos, value){
    this.isHourInError[pos] = value ? 1 : 0;
};
timeCard.SaveButton.prototype.isReady = function() {
    return !!(this.areHoursSet() && this.type && !this.getIsHourInError());
};

timeCard.saveButton = new timeCard.SaveButton(false,false);

timeCard.enabledDisabledSaveButton = function () {
    if (timeCard.saveButton.isReady()) {
        $("#saveButtonTimeCard").prop('disabled', false);
    } else {
        $("#saveButtonTimeCard").prop('disabled', true);
    }
};


timeCard.loseFocusOnDOW = function() {

    var dow00 = $('#dow_00');
    dow00.focusout(function(){
        timeCard.checkHours(timeCard.convertDOWToNumber(dow00),dow00);
    });
    var dow01 = $('#dow_01');
    dow01.focusout(function(){
        timeCard.checkHours(timeCard.convertDOWToNumber(dow01),dow01);
    });
    var dow02 = $('#dow_02');
    dow02.focusout(function(){
        timeCard.checkHours(timeCard.convertDOWToNumber(dow02),dow02);
    });
    var dow03 = $('#dow_03');
    dow03.focusout(function(){
        timeCard.checkHours(timeCard.convertDOWToNumber(dow03),dow03);
    });
    var dow04 = $('#dow_04');
    dow04.focusout(function(){
        timeCard.checkHours(timeCard.convertDOWToNumber(dow04),dow04);
    });
    var dow05 = $('#dow_05');
    dow05.focusout(function(){
        timeCard.checkHours(timeCard.convertDOWToNumber(dow05),dow05);
    });
    var dow06 = $('#dow_06');
    dow06.focusout(function(){
        timeCard.checkHours(timeCard.convertDOWToNumber(dow06),dow06);
    });

    timeCard.checkHours = function(i,dow) {
        timeCard.processDOW(dow);
        timeCard.doHoursExistForWorkTypeDescription(i,dow);
        timeCard.enabledDisabledSaveButton();
    };

    timeCard.processDOW = function(dow) {
        var value = dow.text($(this)).val();
        if (Number(value) == 0) {
            timeCard.setColorSuccess(dow);
            timeCard.setHoursFailure(dow);
        } else if (Number(value) < 0 ||
            Number(value) > 24 ||
            isNaN(Number(value))) {
            timeCard.setFalseState(dow);
        } else {
            value = Math.round(value * 100) / 100;
            dow.text($(this)).val(value);
            timeCard.setTrueState(dow);
        }
    };

    timeCard.setResetState = function(dow) {
        timeCard.setColorSuccess(dow);
    };

    timeCard.setTrueState = function(dow) {
        timeCard.setColorSuccess(dow);
        timeCard.setHoursSuccess(dow);
    };

    timeCard.setFalseState = function(dow) {
        timeCard.setColorFailure(dow);
        timeCard.setHoursFailure(dow);
    };

    timeCard.setColorSuccess = function(dow) {
        dow.css('background-color', 'white');
        timeCard.saveButton.setIsHourInError(timeCard.convertDOWToNumber(dow),false);
    };

    timeCard.setColorFailure = function(dow) {
        dow.css('background-color', 'pink');
        timeCard.saveButton.setIsHourInError(timeCard.convertDOWToNumber(dow),true);
    };

    timeCard.setHoursSuccess = function(dow) {
        timeCard.saveButton.setHours(timeCard.convertDOWToNumber(dow), true);
    };

    timeCard.setHoursFailure = function(dow) {
        timeCard.saveButton.setHours(timeCard.convertDOWToNumber(dow), false);
    };



    timeCard.setTrueStateTableCell = function(cell) {
        cell.style.color = "black";
        cell.style.fontWeight = 'normal';
        cell.style.color = "black";
        cell.style.fontWeight = 'normal';
    };

    timeCard.setFalseStateTableCell = function(cell) {
        cell.style.color = "pink";
        cell.style.fontWeight = 'bold';
        cell.style.color = "pink";
        cell.style.fontWeight = 'bold';
    };

    timeCard.changeNullToZero = function(value) {
        if (value == "") {
            return 0;
        }

        return value;
    };

    timeCard.doHoursExistForWorkTypeDescription = function(column,dow) {

        var colWorkTypeDesc=0;
        var dowValue=0;
        var tblValue=0;

        var workTypeDesc = $('#workType option:selected').text();
        var tblWorkTypeDesc="";

        var table = document.getElementById("timeCardTable");

        // early exit when the time card has no rows.
        if (table == null) {
            return;
        }

        for (var i = 0, row; row = table.rows[i]; i++) {
            // firefox has an issue with .innerText
            var tmpCell = row.cells[colWorkTypeDesc];
            tblWorkTypeDesc = tmpCell.innerText || tmpCell.textContent;
            if (workTypeDesc == tblWorkTypeDesc) {
                dowValue=Number(timeCard.changeNullToZero(dow.val()));
                tblValue=Number(row.cells[column].innerText || row.cells[column].textContent);
                if (dowValue>0 && tblValue>0) {
                    timeCard.setFalseState(dow);
                    timeCard.setFalseStateTableCell(row.cells[column]);
                    timeCard.saveButton.setCalledFromWorkType(false);
                } else if (dowValue==0 && tblValue>0 ||
                           dowValue==0 && tblValue==0 ) {
                    timeCard.setResetState(dow);
                    timeCard.setTrueStateTableCell(row.cells[column]);
                } else if (dowValue>0 && tblValue==0) {
                    timeCard.setTrueState(dow);
                    timeCard.setTrueStateTableCell(row.cells[column]);
                }
            } else {
                if (timeCard.saveButton.getCalledFromWorkType()) {
                    timeCard.setColorSuccess(dow);
                    timeCard.setTrueStateTableCell(row.cells[column]);
                }
            }
        }
    }
};


timeCard.loseFocusOnType = function() {

    var selector=$('#workType');

    timeCard.checkForTableResets = function() {
        for (var i=1;i<8;i++) {
            timeCard.saveButton.setCalledFromWorkType(true);
            timeCard.doHoursExistForWorkTypeDescription(i,timeCard.convertColumnToDOW(i));
        }
        timeCard.saveButton.setCalledFromWorkType(false);
    };

    timeCard.checkType = function() {
        var v1 = Math.floor(selector.val());

        timeCard.saveButton.setType(false);
        timeCard.enabledDisabledSaveButton();
        if (v1 === 0) {
            timeCard.saveButton.setType(false);
            timeCard.enabledDisabledSaveButton();
            selector.empty();
            selector.append("<option value='0'>--Select Type--</option>");
            timeCard.WorkType();
        } else {
            timeCard.checkForTableResets();

            timeCard.saveButton.setType(true);
            timeCard.enabledDisabledSaveButton();
        }
    };

    selector.change(function () {
        timeCard.checkType();
    });
};

timeCard.convertColumnToDOW = function(col) {

    if (col == 1 ) {
        return $('#dow_00');
    }
    if (col == 2 ) {
        return $('#dow_01');
    }
    if (col == 3 ) {
        return $('#dow_02');
    }
    if (col == 4 ) {
        return $('#dow_03');
    }
    if (col == 5 ) {
        return $('#dow_04');
    }
    if (col == 6 ) {
        return $('#dow_05');
    }
    if (col == 7 ) {
        return $('#dow_06');
    }
};

timeCard.convertDOWToNumber = function(dow) {

    var value = dow.selector;

    if (value == "#dow_00") {
        return 1;
    } else if (value  == "#dow_01") {
        return 2;
    } else if (value  == "#dow_02") {
        return 3;
    } else if (value  == "#dow_03") {
        return 4;
    } else if (value  == "#dow_04") {
        return 5;
    } else if (value  == "#dow_05") {
        return 6;
    } else if (value  == "#dow_06") {
        return 7;
    }
};

//timeCard.deleteButton = function() {
//    var value=$("button:contains('deleteButton')").val();
//    //if (value != undefined) {
//        value = value.split(' ').join('_');
//        $("button:contains('deleteButton')").val(value);
//    //}
//};
//















/**
 * Created by richjones on 1/26/16.
 */


// load all javascript once the document is ready.
$(document).ready(function(){

    causeTheTopLineOfTableHeaderToFade();

});

function causeTheTopLineOfTableHeaderToFade() {
    var valueIs = $('#thAlertMessage').val();
    if (typeof valueIs != 'undefined') {

        // reload time card work type drop down on RDBMS failure.
        timeCard.WorkType();

        setTimeout(function () {
            document.getElementById('thAlertMessage').style.display='none';
            $('#thNoAlertMessage').fadeIn(3000);
        }, 10000);
    }
}

//# sourceMappingURL=all.js.map
