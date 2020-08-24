var loading = null;
function myalert(msg) {
  $('.layer-tip').remove();
  var timestamp = (new Date()).valueOf();
  var html = '<div id="'+timestamp+'" class="layer-tip">'+msg+'</div>';
  $('body').append(html);
  var ml = '-'+($('.layer-tip').width()/2+10)+'px';
  $('.layer-tip').css({
    left: '50%',
    marginLeft: ml
  });
  setTimeout(function () {
    $('#'+timestamp).remove();
  }, 3000);
}

function openLoading() {
  var timestamp = (new Date()).valueOf();
  loading = timestamp;;
  var html = '<div id="'+timestamp+'" class="layer-bg">\
    <div class="layer-loader">\
      <div class="pacman">\
      <div></div><div></div><div></div><div></div><div></div>\
    </div>\
  </div>';
  $('body').append(html);
}

function closeLoading() {
  $('#'+loading).remove();
}

function setCookie(name,value) { 
  var Days = 30; 
  var exp = new Date(); 
  exp.setTime(exp.getTime() + Days*24*60*60*1000); 
  document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
} 

function getCookie(name) { 
  var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
  if(arr=document.cookie.match(reg))
    return unescape(arr[2]); 
  else 
    return null; 
}

function delCookie(name) { 
  var exp = new Date(); 
  exp.setTime(exp.getTime() - 1); 
  var cval=getCookie(name); 
  if(cval!=null) 
    document.cookie= name + "="+cval+";expires="+exp.toGMTString(); 
}

function getUrlParam(name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
  var r = window.location.search.substr(1).match(reg);
  if (r != null) {
    return unescape(r[2]);
  } else {
    return null;
  }
}
