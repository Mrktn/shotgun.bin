$(document).ready(function () {
    $(function () {
        $('a[delete-confirm]').click(function (ev) {
            var href = $(this).attr('href');
            if (!$('#dataConfirmModal').length) {
                $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Action irréversible</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button><a class="btn btn-danger" id="dataConfirmOK">Confirmer</a></div></div></div></div>');
            }
            $('#dataConfirmModal').find('.modal-body').text($(this).attr('delete-confirm'));
            $('#dataConfirmOK').attr('href', href);
            $('#dataConfirmModal').modal({show: true});


            return false;
        });

        $('a[unsuscribe-confirm]').click(function (ev) {
            var href = $(this).attr('href');
            if (!$('#dataConfirmModal').length) {
                $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Action irréversible</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button><a class="btn btn-danger" id="dataConfirmOK">Confirmer</a></div></div></div></div>');
            }
            $('#dataConfirmModal').find('.modal-body').text($(this).attr('unsuscribe-confirm'));
            $('#dataConfirmOK').attr('href', href);
            $('#dataConfirmModal').modal({show: true});

            return false;
        });
    });
    var requiredCheckboxes = $('.multiple_choices_form input[type=checkbox]');
    requiredCheckboxes.click(function () {
        var fratrie = $(this).parent().children();
        var atLeastOneIsChecked = fratrie.is(":checked");

        if (atLeastOneIsChecked) {
            fratrie.prop('required', false);
        } else {
            fratrie.prop('required', true);
        }
    });

});

jQuery(document).ready(function ($) {
    $('.readingmore').readmore({moreLink: '<a href="#">Lire la suite</a>',
        lessLink: '<a href="#">Fermer</a>'})
});


function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

function download_csv(header, dat) {
    var csv = header.join(';') + "\n";
    dat.forEach(function (row) {
        //csv += row.join(';');
        row.forEach(function (strr) {
            csv += '"' + decodeHtml(strr) + '"' + ";";
        });
        csv += "\n";
    });

    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURI(csv);
    hiddenElement.target = '_blank';
    hiddenElement.download = 'people.csv';
    hiddenElement.click();
}