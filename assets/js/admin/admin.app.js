/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('bootstrap');

// menu sidebar
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
    $('.openSubmenu').click();
});


// on gére l'icone loading du boutton de validation de chaque formulaire 
$.fn.isValid = function(){
    return this[0].checkValidity()
  }
$("#submit").click(function() { // à test
    $("form").submit(function (){
        var valid = true;
        $("input[type=text], textarea").each(function() {
            if ($(this).isValid() == false) {
                valid = false;
                return false;
            }
        })
        if (valid) {
            $('#b-e-default').hide();
            $('#b-e-loading').show();
        }
    });
});

