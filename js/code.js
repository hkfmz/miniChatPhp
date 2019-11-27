var send_request_data;

function send_request(task, file_url, data_array) {
    $.ajax({
        type: 'POST',
        async: false,
        url: file_url,
        'Content-Type': 'application/Json',
        data: {
            'task': task,
            'allData': data_array
        },
        success: function(data) {
            window.send_request_data = data;
            //console.log(data);
            // $('.error').show().html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("<pre>Ajax Error found-: \n \n" + XMLHttpRequest.responseText + "</pre>");
            $('.error').show().html("<pre>Ajax Error found-: \n \n" + XMLHttpRequest.responseText + "</pre>");
        }
    });
    return send_request_data;
}

//By Hegel Motokoua