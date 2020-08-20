$(document).ready(function () {

    $('body').scrollspy({ target: '#navbar', offset: 300 });

    $("#myInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".dropdown-menu li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        }).length;

        $(".dropdown-menu li:visible").length === 0 ? $('#mutuellesNoResult').removeClass("d-none") : $('#mutuellesNoResult').addClass("d-none");
    });

    $("#healthInsuranceSearchField").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#healthInsurances tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        }).length;

        $("#healthInsurances tr:visible").length === 0 ? $('#mutuellesNoResult').removeClass("d-none") : $('#mutuellesNoResult').addClass("d-none");
    });

});


$(".selector-list>li ").click(function () {
    var datalist = $(this).attr("data-list");
    $(".selector-list>li[data-list='" + datalist + "']").removeClass("active");
    $(this).addClass("active");
});


$('.navbar-nav a').click(function (event) {
    var id = $(this).attr("href");
    var offset = 70;
    var target = $(id).offset().top - offset;
    $('html, body').animate({
        scrollTop: target
    }, 500);
    event.preventDefault();
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



$('form[name="contact"]').submit(function (e) {
    e.preventDefault();
    $('form[name="contact"]>.btn').addClass('disabled').text('Envoi...').prop("disabled", true);
    $.ajax({
        type: 'POST',
        url: './ajaxContact',
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);
            if (data["status"] === 'success') {
                showAlert("<strong>Votre message a bien été envoyé</strong>", "success", 5000);
                $('.invalid-feedback').removeClass('invalid-feedback');
                $('.is-invalid').removeClass('is-invalid');
                $('.form-error-icon').remove();
                $('.form-error-message').remove();
                $('form[name="contact"]>.btn').addClass('btn-success').text('Message envoyé !').prop("disabled", true);
            } else {
                showAlert("<strong>Echec,</strong> vérifiez les champs du formulaire", "danger", 5000);
                var innerHTML = $(data).find('#contact').html();
                $('#contact').html(innerHTML);
                $('form[name="contact"]>.btn').removeClass('disabled').text('Confirmer').prop("disabled", false);
            }
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});


$('.tab-btn').on('click', function (e) {
    e.preventDefault();
    $(this).tab('show');
    $(this).removeClass('active')
})

$('.slot-item').on('click', function (e) {
    var slot = $(this).data("slot");
    day = $.trim($(".list-group-item[data-list='day-" + slot + "'].active").text());
    console.log(day);
    hour = $.trim($(this).text());
    $("#rdv_slot" + slot).val(day + " - " + hour).removeClass("d-none");
    $("#rdv_no_choice").addClass("d-none");
})


$('#modalRdv .close').on('click', function () {
    purgeRdvForm();
})

function purgeRdvForm() {
    $('.slot-item').removeClass("active");
    $('#rdv_slot1').val('').addClass("d-none");
    $('#rdv_slot2').val('').addClass("d-none");
    $('#rdv_slot3').val('').addClass("d-none");
    $("#rdv_no_choice").removeClass("d-none");
    $("#pills-slots").removeClass('active');
    $("#pills-message").removeClass('active')
    $("#pills-home").tab('show');
    $("#slot3").removeClass('active');
    $("#slot2").removeClass('active')
    $("#slot1").tab('show');
    $("#slot3-tab").removeClass('active');
    $("#slot2-tab").removeClass('active')
    $("#slot1-tab").tab('show');
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

$('.showConfirm').on('click', function (e) {
    event.preventDefault();
    data = $(this).data("link");
    message = $(this).data("message");
    $("#modalConfirm #confirmBtn").attr('href', data);
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

$(document).on('submit','form[name="content"]', function (e) {
    e.preventDefault();
    targetSection = $(this).data('section')
    $.ajax({
        type: 'POST',
        url: '/admin/content/'+ $(this).data('id')+'/axjaxUpdate',
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
            $('#'+ targetSection).html(data);
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
        url: '/admin/healthInsurance/'+ $(this).data('id')+'/' + $(this).data('status'),
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
