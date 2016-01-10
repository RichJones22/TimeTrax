
// namespace.
var timeCard = {};

// load all javascript once the document is ready.
$(document).ready(function(){
    timeCard.WorkType();

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







