// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('bootstrap');

global.Cookies = require('js-cookie');

require('./general.js');

require('./header.js');

require('slick-carousel');

require('./home/front.home.js')