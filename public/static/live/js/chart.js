var ws = new WebSocket('ws://192.168.1.199:8812');
ws.onopen = function (e) {

    console.log('connected');
}

ws.onmessage = function (e) {
    console.log('Msg:' + e.data);
    push(e.data)
}

ws.onclose = function (e) {
    console.log('Close')
}

function push(data) {
    var data = JSON.parse(data);
    var logo = data.logo ? data.logo : '';
    var content = data.content ? data.content : '';

    var html = '<div class="comment">\
        <span>'+data.user+'</span>\
        <span>'+data.content+'</span>\
    </div>';

    $("#comments").prepend(html);
}