$(document).ready(function(){
    eventos();
});

function eventos() {
    $.ajax({
        type: "POST",
        url: './eventos',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: '',
        dataType: 'json',
        complete: function() {
            $('#spinner').css('display', 'none');
        },
        success: function(response){
            //console.log(response);
            calendar(response);
        }
    })
};

function calendar(response) {
    $('#calendar').fullCalendar({
        lang: 'pt-br',
        navLinks: true,
        eventLimit: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        events: response,
        /*
        dayClick: function() {
            alert('a day has been clicked!');
        },
        */
    });
}


