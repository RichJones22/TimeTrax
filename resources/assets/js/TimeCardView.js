
// namespace.
var timeCard = {};

// load all javascript once the document is ready.
$(document).ready(function(){
    timeCard.WorkType();
    timeCard.loseFocusOnDOW();
    timeCard.loseFocusOnType();

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
timeCard.SaveButton = function(hours, type) {
    this.hours = hours;
    this.type = type;
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
timeCard.SaveButton.prototype.isReady = function() {
    if (this.hours && this.type) {
        return true;
    }

    return false;
};

timeCard.saveButton = new timeCard.SaveButton(false,false);

timeCard.enabledDisabledSaveButton = function () {
    if (timeCard.saveButton.isReady()) {
        $("#saveButtonTimeCard").prop('disabled', false);
    } else {
        $("#saveButtonTimeCard").prop('disabled', true);
    }
}

// check for single none duplicated words.
timeCard.loseFocusOnDOW = function() {

    var dow00 = $('#dow_00');
    dow00.focusout(function(){
        processDOW(dow00);
    });
    var dow01 = $('#dow_01');
    dow01.focusout(function(){
        processDOW(dow01);
    });
    var dow02 = $('#dow_02');
    dow02.focusout(function(){
        processDOW(dow02);
    });
    var dow03 = $('#dow_03');
    dow03.focusout(function(){
        processDOW(dow03);
    });
    var dow04 = $('#dow_04');
    dow04.focusout(function(){
        processDOW(dow04);
    });
    var dow05 = $('#dow_05');
    dow05.focusout(function(){
        processDOW(dow05);
    });
    var dow06 = $('#dow_06');
    dow06.focusout(function(){
        processDOW(dow06);
    });

    function processDOW(dow) {
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
        }
    });
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










