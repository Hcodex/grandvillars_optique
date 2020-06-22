$(document).ready(function () {

    $('body').scrollspy({ target: '#navbar', offset: 300 });

    $("#myInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".dropdown-menu li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});


$(".selector-list>li ").click(function () {
    var datalist = $(this).attr("data-list");
    console.log(datalist);
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
        url: './ajaxRdv',
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