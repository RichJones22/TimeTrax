
// load all javascript once the document is ready.
$(document).ready(function(){
    loseFocusOnType();
    loseFocusOnDescription();
});

// SaveButton class to save state of required input fields.
function SaveButton(taskType, description) {
    this.taskType = taskType;
    this.description = description;
}
SaveButton.prototype.getType = function() {
    return this.taskType;
};
SaveButton.prototype.setType = function(taskType) {
    this.taskType = taskType;
};
SaveButton.prototype.getDescription = function() {
    return this.description;
};
SaveButton.prototype.setDescription = function(description) {
    this.description = description;
};
SaveButton.prototype.isReady = function() {
    if (this.taskType && this.description) {
        return true;
    }

    return false;
};

var saveButton = new SaveButton(false,false);

function enabledDisabledSaveButton() {
    if (saveButton.isReady()) {
        $("#saveButtonTaskType").prop('disabled', false);
    } else {
        $("#saveButtonTaskType").prop('disabled', true);
    }
}

// rom http://www.mediacollege.com/internet/javascript/text/count-words.html
function countWords(s){
    s = s.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
    s = s.replace(/[ ]{2,}/gi," ");//2 or more space to 1
    s = s.replace(/\n /,"\n"); // exclude newline with a start spacing
    return s.split(' ').length;
}

function isTaskTypeADuplicate() {

    var table = document.getElementById("taskTypeTable");
    for (var i = 0, row; row = table.rows[i]; i++) {
        var t1Str = $('#taskType').text($(this)).val();

        var t2Str = row.cells[0].innerHTML;

        if (t1Str.toUpperCase() === t2Str.toUpperCase()) {
            return true;
        }
    }

    return false;
}

// check for single none duplicated words.
function loseFocusOnType() {
    $("#taskType").focusout(function(){
        var t1Str = $('#taskType').text($(this)).val();


        // only allow one word
        if (countWords(t1Str) > 1) {
            $('#taskType').css('background-color', 'pink');

            $('#taskTypeHeader').text("Error: Type restricted to one word.");
            $('#taskTypeHeader').css('color', 'brown');
            $('#taskTypeHeader').css('font-weight', 'bold');
            saveButton.setType(false);
            enabledDisabledSaveButton();

            return;
        }

        // nothing entered
        if (t1Str.isEmpty()) {
            saveButton.setType(false);
            enabledDisabledSaveButton();

            return;
        }

        if (isTaskTypeADuplicate()) {
            $('#taskType').css('background-color', 'pink');

            $('#taskTypeHeader').text("Error: Type already exists.");
            $('#taskTypeHeader').css('color', 'brown');
            $('#taskTypeHeader').css('font-weight', 'bold');
            saveButton.setType(false);
            enabledDisabledSaveButton();

            return;
        } else {
            $('#taskType').css('background-color', 'white');
            document.getElementById("taskTypeHeader").style.color=$("#taskTypeId").css("color");
            $('#taskTypeHeader').text("Task Type Maintenance");
        }

        // place in correct format
        var tmp = t1Str.toLowerCase();
        tmp = tmp.charAt(0).toUpperCase() + tmp.slice(1);
        $('#taskType').val(tmp);

        saveButton.setType(true);
        enabledDisabledSaveButton();

    });
}

// must have a value.
function loseFocusOnDescription() {
    $("#description").focusout(function(){
        var t1Str = $('#description').text($(this)).val();

        // nothing entered
        if (t1Str.isEmpty()) {
            saveButton.setDescription(false);
            enabledDisabledSaveButton();

            return;
        }

        // place in correct format
        var tmp = t1Str.toLowerCase();
        tmp = tmp.charAt(0).toUpperCase() + tmp.slice(1);
        $('#description').val(tmp);

        saveButton.setDescription(true);
        enabledDisabledSaveButton();

    });
}




