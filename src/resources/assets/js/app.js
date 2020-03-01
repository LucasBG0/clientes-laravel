window.$ = window.jQuery = require('jquery');
window.Tether = require('tether');
require('selectize');
require('bootstrap');

$(document).ready(function() {
    if ( $('#tags').length ) {
        $('#tags').selectize({
            delimiter: ',',
            persist: false,
            valueField: 'tag',
            labelField: 'tag',
            searchField: 'tag',
            options: tags,
            create: function(input) {
                return {
                    tag: input
                }
            }
        });
    }

    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });    

});

