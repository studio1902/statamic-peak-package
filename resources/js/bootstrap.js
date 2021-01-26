import 'alpinejs';

/**
 * Optional, bind jQuery on the window object.
 * jQuery must be installed in order to be used.
 * Install with Node - `npm i jquery`
 */
// window.$ = window.jQuery = require('jquery');

window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Global cookie functions.
 */
window.setCookie = function(name, value, days) {
  let expires = ''
  if (days) {
    const date = new Date()
    date.setTime(date.getTime() + (days*24*60*60*1000))
    expires = '; expires=' + date.toUTCString()
  }
  document.cookie = name + '=' + (value || '')  + expires + '; path=/'
}

window.eraseCookie = function(name) {
  document.cookie = name+'=; Max-Age=-99999999;'
}

window.getCookie = function(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}
