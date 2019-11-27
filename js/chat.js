$(document).ready(function() {
    $('form.chat').on('submit', function(e) {
        e.preventDefault();
        if ($('input[name="message"]').val() != "") {
            /* enable this code snippet is your sever is slow.
             *  this line of code will add your local chat into box and then will sync later
             *
             var clone = $('.show-chat .row').last().clone();
             $('.show-chat .row:last-child').after(clone);
             var chat_text_box = $('.send-chat textarea');
             var last_row = $('.show-chat .row:last-child');// over-right child as newly added row
             last_row.attr('ref','unsaved').attr('id','');
             last_row.find('label.userName').html(localStorage.getItem('userName'));
             last_row.find('span.msg').html(chat_text_box.val().replace(/</g, "&lt;").replace(/>/g, "&gt;"));  // prevent html injection
             */

            scrollBottom($('.show-chat'));
            var data = $(this).serialize();
            $('.message').val('');
            var chat_data = send_request('saveChat', 'controller/ajax.php', data);
            $('.show-chat').html(chat_data);
            scrollBottom($('.show-chat'));
        } else {
            alert('Please enter some text into chat box');
        }
    })
});


function scrollBottom(target) {
    var scroll_to_bottom = target.prop('scrollHeight');
    target.scrollTop(scroll_to_bottom);
}
$(window).load(function(e) {
    scrollBottom($('.show-chat')); // if page refreshed and new chat load with data
});

function updateChat() {
    var chat_data = send_request('sync', 'controller/ajax.php', '');
    $('.show-chat').html(chat_data);
    scrollBottom($('.show-chat'));
}

function parseJson(data) {
    if (data != "") {
        var data = JSON.parse(data);
        console.log(data);
        //return data;
    } else {
        console.log('either data is empty or format is is not matching with json');
    }
}

function checkUserSession() {
    var response = send_request('syncUser', 'controller/ajax.php', '');

    if (response === 0) {
        location.reload();
    }
}

var sessionMaxTime = sessionUpdateTime = 1000;

window.setInterval(function(e) {
    if (sessionUpdateTime <= 0) {
        window.sessionUpdateTime = window.sessionMaxTime;
    }
    checkUserSession();
    window.sessionUpdateTime = eval(sessionUpdateTime - 10);
    console.log(sessionUpdateTime);
}, 1000);
window.setInterval(function(e) {
    updateChat();
}, 1000);

//By Hegel Motokoua