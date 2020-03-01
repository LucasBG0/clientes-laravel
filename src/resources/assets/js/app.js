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

    if ( $('#markdown').length ) {
        $("table").wrap("<div class='table-responsive'>");
        $("table").toggleClass('table table-striped');
        $("#markdown h1, h2").toggleClass('border-bottom pb-2');
        $("#markdown h1, h2, h3, h4, h5").toggleClass('mt-5 mb-4');
        $("p").toggleClass('mb-4');
        $("pre").toggleClass('bg-light');
    }

});

