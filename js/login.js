function login() {
  var email = $('input[name=email]').val().replace(' ', '');
  var password = $('input[name=password]').val().replace(' ', '');

  if (email.match(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/) == null) {
    myalert('email address illegal');
    return;
  }
  if (password.match(/^[a-zA-Z]\w{5,17}$/) == null) {
    myalert('password too weak or illegal');
    return;
  }

  openLoading();
  $.ajax({
    url: BASE_URL+'/api/login.php',
    type: 'POST',
    data: {
      email: email,
      password: password
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.code == 0) {
        setCookie('uid', data.uid);
        setCookie('token', data.token);
        setTimeout(function () {
          window.location.href = BASE_URL+'/html/home.html';
        }, 1500);
      } else {
        closeLoading();
      }
      myalert(data.msg);
    },
    error: function () {
      closeLoading();
      myalert('server offline');
    }
  });
}

function register() {
  var name = $('input[name=name]').val().replace(' ', '');
  var email = $('input[name=email]').val().replace(' ', '');
  var password = $('input[name=password]').val().replace(' ', '');
  var repassword = $('input[name=repassword]').val().replace(' ', '');

  if (name.match(/^[A-Za-z]+$/) == null) {
    myalert('name illegal');
    return;
  }
  if (email.match(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/) == null) {
    myalert('email address illegal');
    return;
  }
  if (password.match(/^[a-zA-Z]\w{5,17}$/) == null) {
    myalert('password too weak or illegal');
    return;
  }
  if (password !== repassword) {
    myalert('confirm password error');
    return;
  }

  openLoading();
  $.ajax({
    url: BASE_URL+'/api/register.php',
    type: 'POST',
    data: {
      name: name,
      email: email,
      password: password
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.code == 0) {
        setTimeout(function () {
          window.location.href = BASE_URL+'/html/login.html';
        }, 1500);
      } else {
        closeLoading();
      }
      myalert(data.msg);
    },
    error: function () {
      closeLoading();
      myalert('server offline');
    }
  });
}

function tokenCheck() {
  var uid = getCookie('uid');
  var token = getCookie('token');

  if (uid != null && uid != '' && token != null && token != '') {
    openLoading();
    $.ajax({
      url: BASE_URL+'/api/token.php',
      type: 'POST',
      async: false,
      data: {
        uid: uid,
        token: token
      },
      success: function (data) {
        data = JSON.parse(data);
        if (data.code == 0) {
          window.location.href = BASE_URL+'/html/home.html';
        } else {
          delCookie('uid');
          delCookie('token');
          closeLoading();
          myalert(data.msg);
        }
      },
      error: function () {
        closeLoading();
        myalert('server offline');
      }
    });
  }
}

$(document).ready(function() {
  tokenCheck();
});