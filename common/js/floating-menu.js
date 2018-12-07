$(document).ready( function() {
    var menu = $('div#floating-menu');
    var offset = menu.offset().top;

    var position = 150; // 固定する画面での座標位置(ピクセルで指定)
    var coodinates = menu.css('top'); // CSSのtopに指定した値を保存
    var origPos = menu.css('position'); // CSSのpositionの値を一時的に保存
      
    //スクロール時のイベント処理
    $(window).scroll(function(){
        var scrollAmount = $(window).scrollTop();
        var newPosition = offset + scrollAmount;

        // CSSの設定を変更してメニューを固定化する
        if(scrollAmount > offset - position){
            menu.css('position', 'fixed')
            menu.css('top', position + "px")
        }else{ // 固定化したメニューを最初の状態に戻している
            menu.css('position', origPos)
            menu.css('top', coodinates)
        }

    });
});

$(document).ready( function() {
    var menu = $('div#floating-index');
    var offset = menu.offset().top;

    var position = 150; // 固定する画面での座標位置(ピクセルで指定)
    var coodinates = menu.css('top'); // CSSのtopに指定した値を保存
    var origPos = menu.css('position'); // CSSのpositionの値を一時的に保存
      
    //スクロール時のイベント処理
    $(window).scroll(function(){
        var scrollAmount = $(window).scrollTop();
        var newPosition = offset + scrollAmount;

        // CSSの設定を変更してメニューを固定化する
        if(scrollAmount > offset - position){
            menu.css('position', 'fixed')
            menu.css('top', position + "px")
        }else{ // 固定化したメニューを最初の状態に戻している
            menu.css('position', origPos)
            menu.css('top', coodinates)
        }

    });
});