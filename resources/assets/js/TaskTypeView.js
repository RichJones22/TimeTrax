
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

taskType.isTaskTypeADuplicate = function() {

    if(!String.prototype.trim) {
        String.prototype.trim = function () {
            return this.replace(/^\s+|\s+$/g,'');
        };
    }

    var table = document.getElementById("taskTypeTable");
    for (var i = 0, row; row = table.rows[i]; i++) {
        var t1Str = $('#taskType01').text($(this)).val();
        t1Str = t1Str.trim();

        var t2Str = row.cells[0].innerHTML;
        t2Str = t2Str.trim();

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

            // set error condition back to a none error color.
            $('#taskType01').css('background-color', 'white');
            $('#taskTypeHeader').text("Task Type Maintenance");
            $('#taskTypeHeader').css('color', 'black');

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
            // set error condition back to a none error color.
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
        var tmp = t1Str.toLowerCase();
        tmp = tmp.charAt(0).toUpperCase() + tmp.slice(1);
        $('#description').val(tmp);

        taskType.saveButton.setDescription(true);
        enabledDisabledSaveButton01();

        // tab to save button
        $("#saveButtonTaskType").focus();

    });
};


// when the taskTypeEditButton is clicked.
taskType.pencilButtonClicked = function () {
    $('.taskTypeEditButton').click(function() {

        // get childern
        //var rowTaskType_id = $('.taskTypeEditButton input[name=rowTaskType_id]').val();

        // parse current row.
        var $row = $(this).closest('tr');
        var $columns = $row.find('td');
        var rowTaskType_id = $columns.find('input[name=rowTaskType_id]').val();

        // derive type and description values.
        var vType = $columns[0].outerText;
        var vDesc = $columns[1].outerText;

        // populate the type and description fields on the form.
        $('#taskType01').val(vType);
        $('#description').val(vDesc);
        $('input[name=saveTaskType_id]').val(rowTaskType_id);

        // set save button to true.
        taskType.saveButton.setType(true);
        taskType.saveButton.setDescription(true);
        enabledDisabledSaveButton01();
    });

};



