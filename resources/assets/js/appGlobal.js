/**
 * Created by richjones on 1/26/16.
 */


// load all javascript once the document is ready.
$(document).ready(function(){

    causeTheTopLineOfTableHeaderToFade();

});

function causeTheTopLineOfTableHeaderToFade() {
    var valueIs = $('#thAlertMessage').val();
    if (typeof valueIs != 'undefined') {
        (setTimeout(function () {
            document.getElementById('thAlertMessage').style.display='none';
            $('#thNoAlertMessage').fadeIn(3000);
        }, 10000))();
    }
}
