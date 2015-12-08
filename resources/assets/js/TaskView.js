
// load all javascript once the document is ready.
$(document).ready(function(){
    String.prototype.isEmpty = function() {
        return (this.length === 0 || !this.trim());
    };

    // populate the type drop-down box on the Task View.
    TaskType();
    isValidHourMinute();
    getStartTime();
    getEndTime();
    loseFocusOnStartTime();
    loseFocusOnEndTime();
    loseFocusOnTaskType();
    enabledDisabledSaveButton();

});

// SaveButton class to save state of required input fields.
function SaveButton(type,startt,endt) {
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

// time validation are performed via http://momentjs.com/.
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
    $('#endt').timepicker({'show2400' : true,
                           'timeFormat': 'H:i',
                           'scrollDefault': 'now',
                           'useSelect' : false });
}

// calculate hours worked and populate the hours worked cell.
function loseFocusOnEndTime() {
    $("#endt").focusout(function(){

        /**
         *  parse start and end times
         * @type {*|jQuery}
         */
        var t1Str = $('#startt-search').text($(this)).val();
        var t1 = t1Str.split(':');

        var t2Str = $('#endt').text($(this)).val();
        if (!isValidHourMinute(t2Str)) {
            $('#endt').focus();
            $('#endt').css('background-color', 'pink');

            saveButton.setEndt(false);
            enabledDisabledSaveButton();

            return;
        }
        else {
            $('#endt').css('background-color', 'white');

            saveButton.setEndt(true);
            enabledDisabledSaveButton();
        }
        var t2 = t2Str.split(':');

        if (!t1Str.isEmpty() && !t2Str.isEmpty()) {
            var beginningTime = moment({H: t1[0], s: t1[1]});
            var endTime = moment({H: t2[0], s: t2[1]});
            if (!beginningTime.isBefore(endTime)) {
                $('#endt').focus();
                $('#endt').css('background-color', 'pink');

                saveButton.setEndt(false);
                enabledDisabledSaveButton();

                return;
            }
            else {
                $('#endt').css('background-color', 'white');

                saveButton.setEndt(true);
                enabledDisabledSaveButton();
            }

            var t1Min = Math.floor(t1[0]) *60 + Math.floor(t1[1]);
            var t2Min = Math.floor(t2[0]) *60 + Math.floor(t2[1]);

            var diff = (t2Min - t1Min)/60;

            $('#hoursWorked').val(Math.round(diff * 10000 )/10000);
        }
    });
}

function loseFocusOnStartTime() {
    $("#startt-search").focusout(function(){
        var t1Str = $('#startt-search').text($(this)).val();
        if (!isValidHourMinute(t1Str)) {
            $('#startt-search').focus();
            $('#startt-search').css('background-color', 'pink');

            saveButton.setStartt(false);
            enabledDisabledSaveButton();

            return;
        }
        else {
            $('#startt-search').css('background-color', 'white');

            saveButton.setStartt(true);
            enabledDisabledSaveButton();
        }
        var t1 = t1Str.split(':');

        var t2Str = $('#endt').text($(this)).val();
        var t2 = t2Str.split(':');



        if (!t1Str.isEmpty() && !t2Str.isEmpty()) {

            var beginningTime = moment({H: t1[0], s: t1[1]});
            var endTime = moment({H: t2[0], s: t2[1]});
            if (!beginningTime.isBefore(endTime)) {
                $('#startt-search').focus();
                $('#startt-search').css('background-color', 'pink');

                saveButton.setStartt(false);
                enabledDisabledSaveButton();

                return;
            }
            else {
                $('#startt-search').css('background-color', 'white');

                saveButton.setStartt(true);
                enabledDisabledSaveButton();
            }

            var t1Min = Math.floor(t1[0]) *60 + Math.floor(t1[1]);
            var t2Min = Math.floor(t2[0]) *60 + Math.floor(t2[1]);

            var diff = (t2Min - t1Min)/60;

            $('#hoursWorked').val(Math.round(diff * 10000 )/10000);
        }
    });
}

function loseFocusOnTaskType() {
    $("#taskType").change(function () {
        var v1 = Math.floor($('#taskType').val());

        if (v1 !== 0) {
            saveButton.setType(true);
            enabledDisabledSaveButton();
        } else {
            saveButton.setType(false);
            enabledDisabledSaveButton();
        }
    });
}

function enabledDisabledSaveButton() {
    if (saveButton.isReady()) {
        $("#saveButton").prop('disabled', false);
    } else {
        $("#saveButton").prop('disabled', true);
    }
}

