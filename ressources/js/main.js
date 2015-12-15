$(function () {
    $(window)
        .load(function () {
            if ($('#idea-possibilites').length) {
                refreshIdeaPossibilities();
            }
        });

    $(document)
        .on('click', '.delete-button', function () {
            $(this).parents('.card:first').remove();
        });

    $('#add-possibility')
        .click(function () {
            if ($('#idea-possibilites').length) {
                addPossibilityLine({ id : 'new[]', name : '', code : '' });
            }
        });

    $('.connection-button')
        .click(function () {
            $('#shadow')
                .stop()
                .css({'display' : 'block', 'opacity' : 0 })
                .animate({ 'opacity' : 0.5 }, 100);
            $('#connection-popup').stop().fadeIn(200);
        });

    $('.inscription-button')
        .click(function () {
            $('#shadow')
                .stop()
                .css({'display' : 'block', 'opacity' : 0 })
                .animate({ 'opacity' : 0.5 }, 100);
            $('#inscription-popup').stop().fadeIn(200);
        });

    $('#shadow')
        .click(function () {
            $('#shadow').stop().fadeOut(100);
            $('.popup').stop().fadeOut(100);
        });
});

function refreshIdeaPossibilities () {
    if ($('#idea-id').length && $('#idea-possibilites').length) {
        var i = 0,
            possibilityDiv = null,
            postParameters = {
                'idea-id': $('#idea-id').val()
            };

        $.post('ressources/ajax/idea-possibilities.php', postParameters, function (response) {
            $('#idea-possibilites').html('');
            if (response.possibilities && response.possibilities.length) {
                for (i = 0; i < response.possibilities.length; i++) {
                    addPossibilityLine(response.possibilities[i]);
                }
            } else {
                $('#idea-possibilites').append('Aucune possibilité pour le moment');
            }
        }, 'json');
    }
}

function addPossibilityLine (possibility) {
    console.log(possibility);
    $.get( "ressources/ajax/possibility.html.twig", function( template ) {
        var myRegexp = /{{ (.{1,15}) }}/g,
            match = null,
            variable = null;

        while (match = myRegexp.exec(template)) {
            variable = possibility[match[1]];
            if (variable != null) {
                template = template.replace(match[0], variable);
            } else {
                console.log('Variable inexistante : ' + match[1]);
            }
        }
        $('#idea-possibilites').append(template);
    });
}