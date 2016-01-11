
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

// populate the work type drop-down box on the Task View.
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
timeCard.SaveButton = function(hours, type, calledFrom) {
    this.hours = hours;
    this.type = type;
    //this.lastColumnInError=col;
    this.calledFromWorkType=calledFrom;
}
timeCard.SaveButton.prototype.getHours = function() {
    return this.hours;
};
timeCard.SaveButton.prototype.setHours = function(hours) {
    this.hours = hours;
};
timeCard.SaveButton.prototype.getType = function() {
    return this.type;
};
timeCard.SaveButton.prototype.setType = function(type) {
    this.type = type;
};
//timeCard.SaveButton.prototype.getCol = function() {
//    return this.lastColumnInError;
//};
//timeCard.SaveButton.prototype.setCol = function(col) {
//    this.lastColumnInError = col;
//};
timeCard.SaveButton.prototype.getCalledFromWorkType = function() {
    return this.calledFromWorkType;
};
timeCard.SaveButton.prototype.setCalledFromWorkType = function(bool) {
    this.calledFromWorkType = bool;
};
timeCard.SaveButton.prototype.isReady = function() {
    if (this.hours && this.type) {
        return true;
    }

    return false;
};

timeCard.saveButton = new timeCard.SaveButton(false,false,false);

timeCard.enabledDisabledSaveButton = function () {
    if (timeCard.saveButton.isReady()) {
        $("#saveButtonTimeCard").prop('disabled', false);
    } else {
        $("#saveButtonTimeCard").prop('disabled', true);
    }
}


timeCard.loseFocusOnDOW = function() {

    var dow00 = $('#dow_00');
    dow00.focusout(function(){
        timeCard.processDOW(dow00);
        timeCard.doHoursExistForWorkTypeDescription(1,dow00);
    });
    var dow01 = $('#dow_01');
    dow01.focusout(function(){
        timeCard.processDOW(dow01);
        timeCard.doHoursExistForWorkTypeDescription(2,dow01);
    });
    var dow02 = $('#dow_02');
    dow02.focusout(function(){
        timeCard.processDOW(dow02);
        timeCard.doHoursExistForWorkTypeDescription(3,dow02);
    });
    var dow03 = $('#dow_03');
    dow03.focusout(function(){
        timeCard.processDOW(dow03);
        timeCard.doHoursExistForWorkTypeDescription(4,dow03);
    });
    var dow04 = $('#dow_04');
    dow04.focusout(function(){
        timeCard.processDOW(dow04);
        timeCard.doHoursExistForWorkTypeDescription(5,dow04);
    });
    var dow05 = $('#dow_05');
    dow05.focusout(function(){
        timeCard.processDOW(dow05);
        timeCard.doHoursExistForWorkTypeDescription(6,dow05);
    });
    var dow06 = $('#dow_06');
    dow06.focusout(function(){
        timeCard.processDOW(dow06);
        timeCard.doHoursExistForWorkTypeDescription(7,dow06);
    });

    timeCard.processDOW = function(dow) {
        var value = dow.text($(this)).val();
        if (Number(value) == 0) {
            timeCard.setColorSuccess(dow);
            timeCard.setHoursFailure(dow);
            return;
        }
        if (Number(value) < 0 ||
            Number(value) > 24 ||
            isNaN(Number(value))) {
            timeCard.setFalseState(dow);
        } else {
            timeCard.setTrueState(dow);
        }
    }

    timeCard.setTrueState = function(dow) {
        timeCard.setColorSuccess(dow);
        timeCard.setHoursSuccess(dow);
    }

    timeCard.setFalseState = function(dow) {
        timeCard.setColorFailure(dow);
        timeCard.setHoursFailure(dow);
    }

    timeCard.setColorSuccess = function(dow) {
        dow.css('background-color', 'white');
    }

    timeCard.setColorFailure = function(dow) {
        dow.css('background-color', 'pink');
    }

    timeCard.setHoursSuccess = function(dow) {
        timeCard.saveButton.setHours(true);
        timeCard.enabledDisabledSaveButton();
    }

    timeCard.setHoursFailure = function(dow) {
        timeCard.saveButton.setHours(false);
        timeCard.enabledDisabledSaveButton();
    }



    timeCard.setTrueStateTableCell = function(cell) {
        cell.style.color = "black";
        cell.style.fontWeight = 'normal';
        cell.style.color = "black";
        cell.style.fontWeight = 'normal';
    }

    timeCard.setFalseStateTableCell = function(cell) {
        cell.style.color = "pink";
        cell.style.fontWeight = 'bold';
        cell.style.color = "pink";
        cell.style.fontWeight = 'bold';
    }

    timeCard.doHoursExistForWorkTypeDescription = function(column,dow) {

        var colWorkTypeDesc=0;

        var workTypeDesc = $('#workType option:selected').text();

        var table = document.getElementById("timeCardTable");
        for (var i = 0, row; row = table.rows[i]; i++) {
            if (workTypeDesc == row.cells[colWorkTypeDesc].innerText) {
                if (dow.val() == row.cells[column].innerText) {
                    timeCard.setFalseState(dow);
                    timeCard.setFalseStateTableCell(row.cells[column]);
                    timeCard.saveButton.setCol(column);
                } else {
                    timeCard.setTrueState(dow);
                    timeCard.setTrueStateTableCell(row.cells[column]);
                }
            } else if (timeCard.saveButton.getCalledFromWorkType()) {
                timeCard.setTrueState(dow);
                timeCard.setTrueStateTableCell(row.cells[column]);
            }
        }
    }
}

timeCard.loseFocusOnType = function() {
    $("#workType").change(function () {
        var v1 = Math.floor($('#workType').val());

        if (v1 === 0) {
            timeCard.saveButton.setType(false);
            timeCard.enabledDisabledSaveButton();
            $("#workType").empty();
            $('#workType').append("<option value='0'>--Select Type--</option>");
            timeCard.WorkType();
        } else {
            timeCard.saveButton.setType(true);
            timeCard.enabledDisabledSaveButton();
            timeCard.checkForTableResets();
        }
    });

    timeCard.checkForTableResets = function() {
        timeCard.saveButton.setCalledFromWorkType(true);
        timeCard.doHoursExistForWorkTypeDescription(1,$('#dow_00'));
        timeCard.doHoursExistForWorkTypeDescription(2,$('#dow_01'));
        timeCard.doHoursExistForWorkTypeDescription(3,$('#dow_02'));
        timeCard.doHoursExistForWorkTypeDescription(4,$('#dow_03'));
        timeCard.doHoursExistForWorkTypeDescription(5,$('#dow_04'));
        timeCard.doHoursExistForWorkTypeDescription(6,$('#dow_05'));
        timeCard.doHoursExistForWorkTypeDescription(7,$('#dow_06'));
        timeCard.saveButton.setCalledFromWorkType(false);
    }



        //for (var i = 1; i < 8; i++) {
        //    timeCard.doHoursExistForWorkTypeDescription(i, timeCard.convertColumnToDOW(i));
        //}
        //
        //if (timeCard.saveButton.getCol()) {
        //    timeCard.saveButton.setCalledFromWorkType(true);
        //    timeCard.doHoursExistForWorkTypeDescription(timeCard.saveButton.getCol(), timeCard.convertColumnToDOW(timeCard.saveButton.getCol()));
        //    timeCard.saveButton.setCol(null);
        //    timeCard.saveButton.setCalledFromWorkType(false);
        //} else {
        //    var colWorkTypeDesc=0;
        //    var found=false;
        //    var workTypeDesc = $('#workType option:selected').text();
        //
        //    var table = document.getElementById("timeCardTable");
        //    for (var i = 0, row; row = table.rows[i]; i++) {
        //        if (workTypeDesc == row.cells[colWorkTypeDesc].innerText) {
        //            found=true;
        //        }
        //    }
        //
        //}

}

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
}

















//# sourceMappingURL=all.js.map
