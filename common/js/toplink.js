$(function() {
    var showFlag = false;
    var topBtn = $('#page-top');    
    topBtn.css('bottom', '-100px');
    var showFlag = false;
    //スクロールが100に達したらボタン表示
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            if (showFlag == false) {
                showFlag = true;
                topBtn.stop().animate({'bottom' : '20px'}, 200); 
            }
        } else {
            if (showFlag) {
                showFlag = false;
                topBtn.stop().animate({'bottom' : '-100px'}, 200); 
            }
        }
    });
    //スクロールしてトップ
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});

jQuery( function($) {
  $('tbody tr[data-href]').addClass('clickable').click( function() {
    window.location = $(this).attr('data-href');
  }).find('a').hover( function() {
    $(this).parents('tr').unbind('click');
  }, function() {
    $(this).parents('tr').click( function() {
      window.location = $(this).attr('data-href');
    });
  });
});