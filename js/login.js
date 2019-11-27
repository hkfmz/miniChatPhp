$(document).ready(function() {
    $('form.login').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        console.log(data);
        var response = send_request('addUser', 'controller/ajax.php', data);
        if (response == 1) {
            $('.error').show().html('loading chat');
            localStorage.setItem('userName', $('form.login').find('input[name="userName"]').val());
            location.reload();
            //alert('chat loading ');
        } else {
            $('.error').show().html(response);
        }
    });

});

//By Hegel Motokoua