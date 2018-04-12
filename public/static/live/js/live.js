var ws = new WebSocket('ws://192.168.1.199:8811');
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

    var html = '<div class="frame">\
        <h3 class="frame-header">\
        <i class="icon iconfont icon-shijian"></i>第一节 01：30\
    </h3>\
    <div class="frame-item">\
        <span class="frame-dot"></span>\
        <div class="frame-item-author">\
        <img src="'+ logo  +'" width="20px" height="20px" /> 马刺\
        </div>\
        <p>'+ content +'</p>\
    </div>\
    ';

    $("#match-result").prepend(html);
}