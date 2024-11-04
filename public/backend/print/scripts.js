$('#print').on('click', function() {
    let CSRF_TOKEN = $('meta[name="csrf-token"').attr('content');

    $.ajaxSetup({
    url: '/print/',
    type: 'POST',
    data: {
    _token: CSRF_TOKEN,
    },
    beforeSend: function() {
    console.log('printing ...');
    },
    complete: function() {
    console.log('printed!');
    }
    });

    $.ajax({
    success: function(viewContent) {
    $.print(viewContent); // This is where the script calls the printer to print the viwe's content.
    }
    });
    });