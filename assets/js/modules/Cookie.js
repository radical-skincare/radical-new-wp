
const Cookie = {
  read: function(name) {
    const nameEQ = name + '=';
    var ca = document.cookie.split(';');
    for (var i=0;i < ca.length;i++) {
      var c = ca[i];
      while (c.charAt(0)==' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
  },
  create: function(name, value, days) {
    let expires = '';
    if (days) {
      var date = new Date();
      date.setTime(date.getTime()+(days*24*60*60*1000));
      expires = '; expires=' + date.toGMTString();
    } else {
      expires = '';
    }
    document.cookie = name + '=' + value + expires + '; path=/';
  },
  erase: function(name) {
    Cookie.create(name, '', -1);
  },
};
