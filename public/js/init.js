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