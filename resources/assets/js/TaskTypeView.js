
// load all javascript once the document is ready.
$(document).ready(function(){
    loseFocusOnTaskType();
    loseFocusOnDescription();
});

// SaveButton class to save state of required input fields.
function SaveButton01(taskType, description) {
    this.taskType = taskType;
    this.description = description;
}
SaveButton01.prototype.getType = function() {
    return this.taskType;
};
SaveButton01.prototype.setType = function(taskType) {
    this.taskType = taskType;
};
SaveButton01.prototype.getDescription = function() {
    return this.description;
};
SaveButton01.prototype.setDescription = function(description) {
    this.description = description;
};
SaveButton01.prototype.isReady = function() {
    if (this.taskType && this.description) {
        return true;
    }

    return false;
};

var saveButton01 = new SaveButton01(false,false);

function enabledDisabledSaveButton01() {
    if (saveButton01.isReady()) {
        $("#saveButtonTaskType").prop('disabled', false);
    } else {
        $("#saveButtonTaskType").prop('disabled', true);
    }
}

// rom http://www.mediacollege.com/internet/javascript/text/count-words.html
function countWords(s){
    //s = s.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
    //s = s.replace(/[ ]{2,}/gi," ");//2 or more space to 1
    //s = s.replace(/\n /,"\n"); // exclude newline with a start spacing
    return s.split(' ').length;
}

function isTaskTypeADuplicate() {

    var table = document.getElementById("taskTypeTable");
    for (var i = 0, row; row = table.rows[i]; i++) {
        var t1Str = $('#taskType01').text($(this)).val();

        var t2Str = row.cells[0].innerHTML;

        if (t1Str.toUpperCase() === t2Str.toUpperCase()) {
            return true;
        }
    }

    return false;
}

// check for single none duplicated words.
function loseFocusOnTaskType() {
    $("#taskType01").focusout(function(){
        var t1Str = $('#taskType01').text($(this)).val();


        // only allow one word
        if (countWords(t1Str) > 1) {
            $('#taskType01').css('background-color', 'pink');

            $('#taskTypeHeader').text("Error: Type restricted to one word.");
            $('#taskTypeHeader').css('color', 'brown');
            $('#taskTypeHeader').css('font-weight', 'bold');
            saveButton01.setType(false);
            enabledDisabledSaveButton01();

            return;
        }

        // nothing entered
        if (t1Str.isEmpty()) {
            saveButton01.setType(false);
            enabledDisabledSaveButton01();

            return;
        }

        if (isTaskTypeADuplicate()) {
            $('#taskType01').css('background-color', 'pink');

            $('#taskTypeHeader').text("Error: Type already exists.");
            $('#taskTypeHeader').css('color', 'brown');
            $('#taskTypeHeader').css('font-weight', 'bold');
            saveButton01.setType(false);
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

        saveButton01.setType(true);
        enabledDisabledSaveButton01();

    });
}

// must have a value.
function loseFocusOnDescription() {
    $("#description").focusout(function(){
        var t1Str = $('#description').text($(this)).val();

        // nothing entered
        if (t1Str.isEmpty()) {
            saveButton01.setDescription(false);
            enabledDisabledSaveButton01();

            return;
        }

        // place in correct format
        var tmp = t1Str.toLowerCase();
        tmp = tmp.charAt(0).toUpperCase() + tmp.slice(1);
        $('#description').val(tmp);

        saveButton01.setDescription(true);
        enabledDisabledSaveButton01();

        // tab to save button
        $("#saveButtonTaskType").focus();

    });
}




