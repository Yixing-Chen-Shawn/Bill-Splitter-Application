$(document).ready(function () {
  openLoading();
  $.ajax({
    url: BASE_URL+'/api/bills.php',
    type: 'POST',
    async: false,
    data: {
      uid: getCookie('uid'),
      token: getCookie('token')
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.code == 0) {
        var bills = data.data;
        for (var i=0; i<bills.length; i++) {
          var bill = bills[i];
          var html = '<div class="row clearfix">\
            <div class="width-8">'+bill.content;
            if (bill.status==1) {
              html += '<span class="success tag">normal</span>';
            } else {
              html += '<span class="warning tag">finish</span>';
            }
            html += '</div>\
            <div class="width-2"><a title="bill" href="bill.html?id='+bill.id+'"><i>&#xe602;</i></a></div>\
          </div>';
          $('.main').append(html);
        }
        closeLoading();
      } else {
        closeLoading();
        myalert(data.msg);
      }
    },
    error: function () {
      closeLoading();
      myalert('server offline');
    }
  })

  var html = '<div class="row clearfix">\
    <a class="btn" title="join in bill" href="add.html">Join in</a>\
  </div>';
  $('.main').append(html);
});

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