$(document).ready(function () {
  $('.height-6').height(0.6*$(document).height());
});
$(window).resize(function () {
  $('.height-6').height(0.6*$(document).height());
})

function start() {
  var uid = getCookie('uid');
  var token = getCookie('token');

  if (uid != null && uid != '' && token != null && token != '') {
    window.location.href = BASE_URL+'/html/home.html';
  } else {
    window.location.href = BASE_URL+'/html/login.html';
  }
}