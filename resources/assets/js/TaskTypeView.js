
// namespace for userTaskView.blade.php
var taskType = {};

// load all javascript once the document is ready.
$(document).ready(function(){
    taskType.loseFocusOnTaskType();
    taskType.loseFocusOnDescription();
    taskType.causeTheTopLineOfTableHeaderToFade();
    taskType.pencilButtonClicked();
});

// SaveButton class to save state of required input fields.
taskType.SaveButton = function(bTaskType, bDescription,vTaskType, vDescription) {
    this.bTaskType = bTaskType;
    this.bDescription = bDescription;
    this.vTaskType = vTaskType;
    this.vDescription = vDescription
}
taskType.SaveButton.prototype.getType = function() {
    return this.bTaskType;
};
taskType.SaveButton.prototype.setType = function(bTaskType) {
    this.bTaskType = bTaskType;
};
taskType.SaveButton.prototype.getDescription = function() {
    return this.bDescription;
};
taskType.SaveButton.prototype.setDescription = function(bDescription) {
    this.bDescription = bDescription;
};
taskType.SaveButton.prototype.getTypeValue = function() {
    return this.vTaskType;
};
taskType.SaveButton.prototype.setTypeValue = function(vTaskType) {
    //if (vTaskType !== "") {
        this.vTaskType = vTaskType;
    //}
};

taskType.SaveButton.prototype.isReady = function() {
    if (this.bTaskType && this.bDescription) {
        return true;
    }

    return false;
};

taskType.saveButton = new taskType.SaveButton(false,false,"","");

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
        }, 10000))();
    }
};

// rom http://www.mediacollege.com/internet/javascript/text/count-words.html
taskType.countWords = function countWords(s){
    //s = s.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
    //s = s.replace(/[ ]{2,}/gi," ");//2 or more space to 1
    //s = s.replace(/\n /,"\n"); // exclude newline with a start spacing
    return s.split(' ').length;
};

taskType.isTaskTypeADuplicate = function(t1Str, id) {

    if(!String.prototype.trim) {
        String.prototype.trim = function () {
            return this.replace(/^\s+|\s+$/g,'');
        };
    }

    // string to compare
    //var t1Str = $('#taskType').text($(this)).val();
    t1Str = t1Str.trim();

    var table = document.getElementById("taskTypeTable");
    for (var i = 0, row; row = table.rows[i]; i++) {

        //var t2Str = row.cells[0].innerHTML;
        var t2Str = $(row).find('input[name=rowTaskType_type]');
        var typeRowId = $(t2Str).attr('id').split('_');

        var t2Str = $(t2Str).val();

        t2Str = t2Str.trim();

        if (id === null) {
            if (t1Str.toUpperCase() === t2Str.toUpperCase()) {
                return true;
            }
        } else {
            if (id !== typeRowId[1]) {
                if (t1Str.toUpperCase() === t2Str.toUpperCase()) {
                    return true;
                }
            }
        }


    }

    return false;
};

taskType.allowOneWord = function(sel)
{
    words = $(sel).val();

    if (taskType.countWords(words) > 1) {
        $(sel).css('background-color', 'pink');

        $('#taskTypeHeader').text("Error: Type restricted to one word.");
        $('#taskTypeHeader').css('color', 'brown');
        $('#taskTypeHeader').css('font-weight', 'bold');
        taskType.saveButton.setType(false);
        enabledDisabledSaveButton01();

        return false;
    }

    return true;
};

taskType.isTaskTypeEmpty = function(sel)
{
    words = $(sel).val();

    if (words.isEmpty()) {
        $(sel).css('background-color', 'pink');

        // set error condition.
        $('#taskTypeHeader').text("Error: Type must contain a value.");
        $('#taskTypeHeader').css('color', 'brown');
        $('#taskTypeHeader').css('font-weight', 'bold');
        taskType.saveButton.setType(false);
        enabledDisabledSaveButton01();

        return false;
    } else {
        // set error condition back to a none error color.
        $('#taskType').css('background-color', 'white');
        document.getElementById("taskTypeHeader").style.color=$("#taskTypeId").css("color");
        $('#taskTypeHeader').text("Task Type Maintenance");

        return true;
    }

};

taskType.placeInCorrectFormat = function(str) {
    var tmp = str.toLowerCase();
    tmp = tmp.charAt(0).toUpperCase() + tmp.slice(1);

    return tmp;
};

// check for single none duplicated words.
taskType.loseFocusOnTaskType = function() {
    $("#taskType").focusout(function(){
        var t1Str = $('#taskType').text($(this)).val();
        var typeRow = $('#taskType').text($(this));

        // only allow one word
        if (! taskType.allowOneWord(typeRow)) {
            return false;
        }

        if (taskType.isTaskTypeADuplicate($('#taskType').val())) {
            $('#taskType').css('background-color', 'pink');

            $('#taskTypeHeader').text("Error: Type already exists.");
            $('#taskTypeHeader').css('color', 'brown');
            $('#taskTypeHeader').css('font-weight', 'bold');
            taskType.saveButton.setType(false);
            enabledDisabledSaveButton01();

            return false;
        } else {
            // set error condition back to a none error color.
            $('#taskType').css('background-color', 'white');
            //document.getElementById("taskTypeHeader").style.color=$("#taskTypeId").css("color");
            $('#taskTypeHeader').text("Task Type Maintenance");
            $('#taskTypeHeader').css('color', 'black');
        }

        // place in correct format
        $('#taskType').val(taskType.placeInCorrectFormat(t1Str));

        taskType.saveButton.setType(true);
        enabledDisabledSaveButton01();

        return true;

    });
};

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
        $('#description').val(taskType.placeInCorrectFormat(t1Str));

        taskType.saveButton.setDescription(true);
        enabledDisabledSaveButton01();

        // tab to save button
        $("#saveButtonTaskType").focus();

    });
};


// when the taskTypeEditButton is clicked.
taskType.pencilButtonClicked = function () {

    $('.rowTaskType').focusin(function() {
        var row = $(this).closest('tr');

        if (appGlobal.ttvTypeClearText) {
            if (row.find('input[name=rowTaskType_type]').val() !== "") {
                taskType.saveButton.setTypeValue(row.find('input[name=rowTaskType_type]').val());
                row.find('input[name=rowTaskType_type]').val("");
            }
        }
    });

    $('.rowTaskType').focusout(function() {
        var row = $(this).closest('tr');
        var typeRow = $(row.find('input[name=rowTaskType_type]'));
        var typeRowId = $(typeRow).attr('id').split('_');
        var $whichOne = typeRowId;

        //$('.taskTypeEditButton').trigger( "change", {'whichOne': $whichOne});
        taskType.editGridItems({'outerObj': this, 'whichOne': $whichOne});
    });

    $('.rowTaskDesc').focusout(function() {
        var row = $(this).closest('tr');
        var descRow = $(row.find('input[name=rowTaskDesc_desc]'));
        var descRowId = $(descRow).attr('id').split('_');
        var $whichOne = descRowId;

        //$('.taskTypeEditButton').trigger( "change", {'whichOne': $whichOne});
        taskType.editGridItems({'outerObj': this, 'whichOne': $whichOne});
    });
};

taskType.editGridItems = function(params) {
    var row = $(params.outerObj).closest('tr');
    var columns = row.find('td');

    // find values for Type and Description
    var vType = row.find('input[name=rowTaskType_type]').val();
    var vDesc = row.find('input[name=rowTaskDesc_desc]').val();

    var typeRow = $(row.find('input[name=rowTaskType_type]'));
    var typeRowVal = $(typeRow).val();
    var typeRowId = $(typeRow).attr('id').split('_');

    // only process the row that changed.
    if (params.whichOne[1] === typeRowId[1]) {
        // perform validations.
        if (! taskType.allowOneWord(typeRow)) {
            return false;
        }

        if (! taskType.isTaskTypeEmpty(typeRow)) {
            return false;
        }

        if (taskType.isTaskTypeADuplicate(typeRowVal, typeRowId[1])) {
            $(row.find('input[name=rowTaskType_type]')).css('background-color', 'pink');

            $('#taskTypeHeader').text("Error: Type already exists.");
            $('#taskTypeHeader').css('color', 'brown');
            $('#taskTypeHeader').css('font-weight', 'bold');
            taskType.saveButton.setType(false);
            enabledDisabledSaveButton01();

            return false;
        } else {
            // set error condition back to a none error color.
            $(typeRow).css('background-color', 'white');
            document.getElementById("taskTypeHeader").style.color=$(typeRow).css("color");
            $('#taskTypeHeader').text("Task Type Maintenance");
        }

        // place in correct format.
        vType = taskType.placeInCorrectFormat(vType);
        vDesc = taskType.placeInCorrectFormat(vDesc);

        // update client with new format.
        row.find('input[name=rowTaskType_type]').val(vType);
        row.find('input[name=rowTaskDesc_desc]').val(vDesc);

        // bundle data for server send.
        var rowTaskType_id = columns.find('input[name=rowTaskType_id]').val();
        var data = {
            id: rowTaskType_id,
            type: vType,
            desc: vDesc,
            client_id: appGlobal.clientId
        };
        data = JSON.stringify(data);
        data = {data: data};

        // send request to server
        taskType.ajaxGetReq(data, rowTaskType_id);

        // set save button to true.
        taskType.saveButton.setType(false);
        taskType.saveButton.setDescription(false);
        enabledDisabledSaveButton01();
    }
};

taskType.ajaxGetReq = function(data, id) {
    $.ajax({
        type: "GET",
        url:  appGlobal.taskTypeURI + id + appGlobal.update,
        contentType: "application/json; charset=utf8",
        data: data,
        dataType: "json"
    });
};



