function displaytext(textid, ischecked, distext) {
  document.getElementById(textid).innerHTML = distext;
  if (ischecked == true) {
    document.getElementById(textid).style.display = "block";
  } else {
    document.getElementById(textid).style.display = "none";
  }
}

function plus(chk, item, prime) {
  chk.value++;
  item.value = parseInt(prime.value) * parseInt(chk.value);
}

function minus(chk, item, prime) {
  chk.value--;
  item.value = parseInt(prime.value) * parseInt(chk.value);
  if (chk.value == 0) {
    chk.value = 1
    item.value = parseInt(prime.value) * parseInt(chk.value);
  }
}

function calc(chk, item, prime) {
  item.value = parseInt(prime.value) * parseInt(chk.value);
}

$(function() {

  var offsetY = -10;
  var time = 500;

  $('a[href^=#]').click(function() {
    var target = $(this.hash);
    if (!target.length) return;
    var targetY = target.offset().top + offsetY;
    $('html,body').animate({
      scrollTop: targetY
    }, time, 'swing');
    window.history.pushState(null, null, this.hash);
    return false;
  });
});
