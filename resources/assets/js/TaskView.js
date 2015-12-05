
$(document).ready(function(){
   // populate the type drop-down box on the Task View.
   TaskType();
});


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