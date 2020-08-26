$(document).ready(function () {

    $("#healthInsuranceSearchField").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#healthInsurances tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        }).length;

        $("#healthInsurances tr:visible").length === 0 ? $('#mutuellesNoResult').removeClass("d-none") : $('#mutuellesNoResult').addClass("d-none");
    });

    bsCustomFileInput.init()

});


function showAlert(message, type, closeDelay) {

    var $cont = $("#alerts-container");

    if ($cont.length == 0) {
        // alerts-container does not exist, create it
        $cont = $('<div id="alerts-container" class="msg-box">')
            .appendTo($("body"));
    }

    // default to alert-info; other options include success, warning, danger
    type = type || "info";

    // create the alert div
    var alert = $('<div>')
        .addClass("fade in show alert alert-" + type)
        .append(
            $('<button type="button" class="close" data-dismiss="alert">')
                .append("&times;")
        )
        .append(message);

    // add the alert div to top of alerts-container, use append() to add to bottom
    $cont.prepend(alert);

    // if closeDelay was passed - set a timeout to close the alert
    if (closeDelay)
        window.setTimeout(function () { alert.alert("close") }, closeDelay);
}





$('#nextbtn').on('click', function (e) {
    e.preventDefault();
    form = $('form[name="rdv"]').get(0);
    $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    console.log(form);
    $.ajax({
        type: 'POST',
        url: './ajaxRdv',
        data: new FormData(form),
        contentType: false,
        processData: false,
        success: function (data) {
            if (data["status"] === 'success') {
                $('.invalid-feedback').removeClass('invalid-feedback');
                $('.is-invalid').removeClass('is-invalid');
                $('.form-error-icon').remove();
                $('.form-error-message').remove();
                $("#pills-home").removeClass('active')
                $("#pills-slots").tab('show');
            } else {
                var innerHTML = $(data).find('#rdvFormStep1').html();
                $('#rdvFormStep1').html(innerHTML);
            };
            $('#nextbtn').html('Suivant');
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on('click', '.showConfirm', function (e) {
    event.preventDefault();
    data = $(this).data("link");
    ajax = $(this).data("ajax");
    message = $(this).data("message");

    $("#modalConfirm #confirmBtn")
    .attr('href', data)
    .addClass(ajax);
    $("#modalConfirm .modal-body").html('<p>' + message + '</p>');
    $("#modalConfirm").modal();
})

function showMonth(date) {
    $.ajax({
        type: 'GET',
        url: "calendar/" + date,
        dataType: "html",
        success: function (response) {
            var innerHTML = $(response).find('#calendar').html();
            $('#calendar').html(innerHTML);
        },
        error: function (errorThrown) {
            console.log(errorThrown);
            console.log("There is an error with AJAX!");
        }
    });
}

$('#closing_days_startDate').change(function (e) {
    $('#closing_days_endDate').val(this.value);
});

$('#closing_days_endDate').change(function (e) {
    if (this.value < $('#closing_days_startDate').val()) {
        $('#closing_days_startDate').val(this.value);
    }
});

$(document).on('submit', 'form[name="content"]', function (e) {
    e.preventDefault();
    targetSection = $(this).data('section')
    $.ajax({
        type: 'POST',
        url: '/admin/content/' + $(this).data('id') + '/axjaxUpdate',
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
            $('#' + targetSection).html(data);
            showAlert("<strong>Contenu modifié</strong>", "success", 5000);
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});


$(document).on('submit', 'form[name="content_icon"]', function (e) {
    e.preventDefault();
    targetSection = $(this).data('section')
    $.ajax({
        type: 'POST',
        url: '/admin/content/' + $(this).data('id') + '/axjaxUpdate',
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
            $('#' + targetSection).html(data);
            showAlert("<strong>Contenu modifié</strong>", "success", 5000);
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on('click', '.healtInsuranceSetStatus', function (e) {
    e.preventDefault();
    target = $(this);
    $.ajax({
        type: 'POST',
        url: '/admin/healthInsurance/' + $(this).data('id') + '/' + $(this).data('status'),
        success: function (data) {
            $(target).parent().children()
                .addClass("bg-secondary border-secondary text-dark"),
                $(target).removeClass("bg-secondary text-dark");
            showAlert("<strong>Couverture modifiée</strong>", "success", 700);
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on('click', '.btn-edit', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: '/admin/content/' + $(this).data('id') + '/axjaxContentFormCreate',
        success: function (data) {
            $('#modalContentForm').replaceWith(data);
            $("#modalContentForm").modal();
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on('submit', 'form[name="upload"]', function (e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: '/admin/upload',
        data: new FormData(this),
        contentType: false,
        processData: false,
        xhr: function () {
            //upload Progress
            var xhr = $.ajaxSettings.xhr();
            if (xhr.upload) {
                xhr.upload.addEventListener('progress', function (event) {
                    var percent = 0;
                    var position = event.loaded || event.position;
                    var total = event.total;
                    if (event.lengthComputable) {
                        percent = Math.ceil(position / total * 100);
                        $('.progress').removeClass('d-none');
                    }
                    if (percent == 100) {
                        $('.progress-bar').text("Envoi terminé");
                        $('.progress-bar').width(percent + "%");

                    } else {
                        $('.progress-bar').text(percent + "%");
                        $('.progress-bar').width(percent + "%");
                    }
                }, true);
            }
            return xhr;
        },//end upload progress
        success: function (data) {
            console.log(data);
            $('#mediaTable tr:last').after(data);
            $("#modalUploadForm").modal('hide');
            showAlert("<strong>Upload terminé</strong>, L\'image a été ajoutée avec succès", "success", 5000);
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on('click', '.ajaxDeleteMedia', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: $(this).attr("href"),
        success: function (data) {
            console.log(data);
            $("#mediaRow"+data).remove();
            $("#modalConfirm").modal('hide')
            showAlert("Média supprimé avec succès", "success", 5000);
        },
        error: function (data) {
            showAlert("<strong>Erreur</>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

