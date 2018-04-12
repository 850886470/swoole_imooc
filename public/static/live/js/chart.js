var ws = new WebSocket('ws://singwa.swoole.com:8812');
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