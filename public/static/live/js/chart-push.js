$(function () {
    $("#discuss-box").keydown(function (e) {
        if (e.keyCode == 13) {
            //回车
            var text = $(this).val()
            var url = "http://singwa.swoole.com:8811/?s=index/chart/index"

            var data = {content: text,game_id:1}

            $.post(url,data,function(res){
                $(this).val("")
            },'json')
        }
    })
})