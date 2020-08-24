$('input[name=nmail]').change(function () {
  openLoading();
  var email = $('input[name=nmail]:checked').val();
  if (email == '' || email == null) {
    email = 0;
  }
  $.ajax({
    url: BASE_URL+'/api/email.php',
    type: 'POST',
    data: {
      uid: getCookie('uid'),
      token: getCookie('token'),
      email: email
    },
    complete: function () {
      closeLoading();
    },
    success: function (data) {
      data = JSON.parse(data);
      myalert(data.msg);
    },
    error: function () {
      myalert('server offline');
    }
  });
});

function pay() {
  openLoading();
  $.ajax({
    url: BASE_URL+'/api/pay.php',
    type: 'POST',
    data: {
      uid: getCookie('uid'),
      token: getCookie('token')
    },
    complete: function () {
      closeLoading();
    },
    success: function (data) {
      data = JSON.parse(data);
      myalert(data.msg);
      if (data.code == 0) {
        setTimeout(function () {
          window.location.reload();
        }, 1500);
      }
    },
    error: function () {
      myalert('server offline');
    }
  });
}

function logout() {
  openLoading();
  $.ajax({
    url: BASE_URL+'/api/logout.php',
    type: 'POST',
    data: {
      uid: getCookie('uid')
    },
    complete: function () {
      closeLoading();
    },
    success: function (data) {
      data = JSON.parse(data);
      myalert(data.msg);
      if (data.code == 0) {
        delCookie('uid');
        delCookie('token');
        setTimeout(function () {
          window.location.href = BASE_URL+'/index.html';
        }, 1000);
      }
    },
    error: function () {
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
      complete: function () {
        closeLoading();
      },
      success: function (data) {
        data = JSON.parse(data);
        if (data.code != 0) {
          delCookie('uid');
          delCookie('token');
          window.location.href = BASE_URL+'/html/login.html';
        }
      },
      error: function () {
        myalert('server offline');
      }
    });
  } else {
    window.location.href = BASE_URL+'/html/login.html';
  }
}

(function($) {
  tokenCheck();
}());