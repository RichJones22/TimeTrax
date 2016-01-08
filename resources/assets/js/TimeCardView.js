
// namespace.
var TimeCard = {};

// load all javascript once the document is ready.
$(document).ready(function(){

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
            $("#formNext").attr("action", "http://timetrax.dev/timeCard/" + (myVal.toISOString()).substr(0,10));
            $("#formNext").submit();
        }
    });
});

// populate the work type drop-down box on the Task View.
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







