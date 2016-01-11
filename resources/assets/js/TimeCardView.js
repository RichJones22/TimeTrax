
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
















