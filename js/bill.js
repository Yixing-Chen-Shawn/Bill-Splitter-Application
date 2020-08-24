$('label.type').click(function () {
  $('input[type=radio][name=type]').each(function () {
    $(this).next().removeClass('active');
  });
  $(this).addClass('active');
})

function add () {
  var content = $('input[name=content]').val().replace(' ', '');
  var total = $('input[name=total]').val().replace(' ', '');
  var number = $('input[name=number]').val().replace(' ', '');
  var type = $('input[name=type]:checked').val().replace(' ', '');

  if (content == '') {
    myalert('content is empty');
    return;
  }
  if (total.match(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/) == null) {
    myalert('total illegal');
    return;
  }
  if (number.match(/^[1-9]\d*$/) == null) {
    myalert('number illegal');
    return;
  }
  if (type != 1 && type != 2) {
    myalert('type illegal');
    return;
  }

  openLoading();
  $.ajax({
    url: BASE_URL+'/api/addBill.php',
    type: 'POST',
    data: {
      uid: getCookie('uid'),
      token: getCookie('token'),
      content: content,
      total: total,
      number: number,
      type: type
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.code == 0) {
        setTimeout(function () {
          window.location.href = BASE_URL+'/html/add.html?key='+data.key;
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

function billByKey(key) {
  $.ajax({
    url: BASE_URL+'/api/keyBill.php',
    type: 'POST',
    data: {
      key: key,
      uid: getCookie('uid'),
      token: getCookie('token')
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.code == 0) {
        var bill = data.data;
        $('span.remain').html(bill.remain+'%');
        if (bill.type == 1) {
          $('.percent').hide();
        }
      } else {
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

function join() {
  var key = $('input[name=key]').val().replace(' ', '');
  var percent = $('input[name=percent]').val().replace(' ', '');

  if (key == '') {
    myalert('key is empty');
    return;
  }
  if (parseInt(percent) > parseInt($('.remain').html().replace('%', ''))) {
    myalert('percent is too much');
    return;
  }

  openLoading();
  $.ajax({
    url: BASE_URL+'/api/join.php',
    type: 'POST',
    data: {
      key: key,
      uid: getCookie('uid'),
      token: getCookie('token'),
      percent: percent
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.code == 0) {
        var bill = data.data;
        setTimeout(function () {
          window.location.href = BASE_URL+'/html/bill.html?id='+bill.id;
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

function billDetail(id) {
  $.ajax({
    url: BASE_URL+'/api/bill.php',
    type: 'POST',
    data: {
      id: id,
      uid: getCookie('uid'),
      token: getCookie('token')
    },
    complete: function() {
      closeLoading();
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.code == 0) {
        var bill = data.data;
        var allPercent = 0;
        var paidPercent = 0;
        for (var i=0; i<bill.member.length; i++) {
          var member = bill.member[i];
          var html = '<div class="row clearfix">\
            <div class="width-3">'+member.name+'</div>\
            <div class="width-2">$'+member.price+'</div>\
            <div class="width-2">'+member.percent+'%</div>\
            <div class="width-3">';
            if (member.status == 0) {
              html += '<span class="warning">Waitting</span>';
            } else if (member.status == 1) {
              paidPercent += parseFloat(member.percent);
              html += '<span class="success">Paid</span>';
            }
            html += '</div>\
          </div>';
          allPercent += parseFloat(member.percent);
          $('.main').append(html);
        }
        $('.bill .total').html('$'+bill.price+' Total');
        $('.bill .percent').html(allPercent+'%<span class="tag">'+paidPercent+'% Paid</span>');
        $('.bill .key').html('Key: '+bill.key);
        if (bill.status == 1) {
          $('.bill .status').html('<span class="tag bsuccess">Normal</span>');
        } else {
          $('.bill .status').html('<span class="tag bwarning">Finished</span>');
        }
      } else {
        myalert(data.msg);
      }
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