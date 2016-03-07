
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














