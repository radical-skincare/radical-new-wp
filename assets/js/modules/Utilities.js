
const Utilities = {
  hasNumber: function (myString) {
    return /\d/.test(myString)
  },
  getAllUrlParams: function(url) {
    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
    // we'll store the parameters here
    var obj = {};
    // if query string exists
    if (queryString) {
      // stuff after # is not part of query string, so get rid of it
      queryString = queryString.split('#')[0];
      // split our query string into its component parts
      var arr = queryString.split('&');
      for (var i = 0; i < arr.length; i++) {
        // separate the keys and the values
        var a = arr[i].split('=');
        // set parameter name and value (use 'true' if empty)
        var paramName = a[0];
        var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];
        // (optional) keep case consistent
        paramName = paramName.toLowerCase();
        if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();
        // if the paramName ends with square brackets, e.g. colors[] or colors[2]
        if (paramName.match(/\[(\d+)?\]$/)) {
          // create key if it doesn't exist
          var key = paramName.replace(/\[(\d+)?\]/, '');
          if (!obj[key]) obj[key] = [];

          // if it's an indexed array e.g. colors[2]
          if (paramName.match(/\[\d+\]$/)) {
            // get the index value and add the entry at the appropriate position
            var index = /\[(\d+)\]/.exec(paramName)[1];
            obj[key][index] = paramValue;
          } else {
            // otherwise add the value to the end of the array
            obj[key].push(paramValue);
          }
        } else {
          // we're dealing with a string
          if (!obj[paramName]) {
            // if it doesn't exist, create property
            obj[paramName] = paramValue;
          } else if (obj[paramName] && typeof obj[paramName] === 'string'){
            // if property does exist and it's a string, convert it to an array
            obj[paramName] = [obj[paramName]];
            obj[paramName].push(paramValue);
          } else {
            // otherwise add the property
            obj[paramName].push(paramValue);
          }
        }
      }
    }
    return obj;
  },
  phoneMask: function( $this ) {
    let x = $this
      .val()
      .replace(/\D/g, '')
      .match(/(\d{0,3})(\d{0,3})(\d{0,4})/)
    let phone = !x[2]
      ? x[1]
      : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '')
    $this.val(phone);
  },
  numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
  },
  getDayOfWeekName: function(d) {
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
    return days[d]
  },
  getDateNth: function(d) {
    if (d > 3 && d < 21) {
      return 'th'
    }
    switch (d % 10) {
      case 1:
        return 'st'
      case 2:
        return 'nd'
      case 3:
        return 'rd'
      default:
        return 'th'
    }
  },
  abbrvGetMonth: function (m) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    return months[m]
  },
  getMonthFullName: function (m) {
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    return months[m]
  },
  formatHoursTo12: function(hours) {
    return hours % 12 || 12
  },
  getAMPM: function(hours) {
    return hours >= 12 ? 'pm' : 'am'
  },
  daysUntil: function (dateText) {
    const today = new Date()
    const endDate = new Date(dateText)
    const difference = endDate.getTime() - today.getTime()
    return Math.ceil(difference / (1000 * 3600 * 24))
  },
  toDateTime: function (secs) {
    let t = new Date(1970, 0, 1) // Epoch
    t.setSeconds(secs)
    return t
  },
  percentageOff: function (price, percentageValue) {
    return parseFloat(price * (1 - percentageValue / 100)).toFixed(2).replace('.00','')
  },
  updateOrAppendQueryParameter: function(url, paramName, paramValue) {
    let urlObj = new URL(url)
    let searchParams = urlObj.searchParams
    if (searchParams.has(paramName)) {
      // Update existing parameter value
      searchParams.set(paramName, paramValue)
    } else {
      // Append new parameter
      searchParams.append(paramName, paramValue)
    }
    // Reconstruct the URL with the updated/added query parameters
    urlObj.search = searchParams.toString()
    return urlObj.toString()
  },
}
