$(document).ready(function () {

    $("#healthInsuranceSearchField").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#healthInsurances tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        }).length;

        $("#healthInsurances tr:visible").length === 0 ? $("#mutuellesNoResult").removeClass("d-none") : $("#mutuellesNoResult").addClass("d-none");
    });

    bsCustomFileInput.init();

});


function showAlert(message, type, closeDelay) {

    var $cont = $("#alerts-container");

    if ($cont.length == 0) {
        // alerts-container does not exist, create it
        $cont = $("<div id='alerts-container' class='msg-box'>")
            .appendTo($("body"));
    }

    // default to alert-info; other options include success, warning, danger
    type = type || "info";

    // create the alert div
    var alert = $("<div>")
        .addClass("fade in show alert alert-" + type)
        .append(
            $("<button type='button' class='close' data-dismiss='alert'>")
                .append("&times;")
        )
        .append(message);

    // add the alert div to top of alerts-container, use append() to add to bottom
    $cont.prepend(alert);

    // if closeDelay was passed - set a timeout to close the alert
    if (closeDelay)
        window.setTimeout(function () { alert.alert("close") }, closeDelay);
}

$("#myTab a").on("click", function (e) {
    e.preventDefault()
    $(this).tab("show")
})

$(document).on("click", ".showConfirm", function (e) {
    event.preventDefault();
    data = $(this).data("link");
    ajax = $(this).data("ajax");
    message = $(this).data("message");
    $("#modalConfirm #confirmBtn")
        .removeClass()
        .attr("href", data)
        .addClass("btn btn-info " + ajax);
    $("#modalConfirm .modal-body").html("<p>" + message + "</p>");
    $("#modalConfirm").modal();
})

function showMonth(date) {
    $.ajax({
        type: "GET",
        url: "calendar/" + date,
        dataType: "html",
        success: function (response) {
            var innerHTML = $(response).find("#calendar").html();
            $("#calendar").html(innerHTML);
        },
        error: function (errorThrown) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
}

$("#closing_days_startDate").change(function (e) {
    $("#closing_days_endDate").val(this.value);
});

$("#closing_days_endDate").change(function (e) {
    if (this.value < $("#closing_days_startDate").val()) {
        $("#closing_days_startDate").val(this.value);
    }
});

$(document).on("submit", "form[name='content']", function (e) {
    e.preventDefault();
    targetSection = $(this).data("target");
    $(".submit-btn").html("<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>");
    $.ajax({
        type: "POST",
        url: "/admin/content/" + $(this).data("id") + "/axjaxContentFormCreate",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
            if (data["status"] === "success") {
                $("#" + targetSection).replaceWith(data["render"]);
                $("#modalContentForm").modal("hide");
                showAlert("<strong>Contenu modifié</strong>", "success", 5000);
            } else {
                showAlert("<strong>Echec,</strong> vérifiez les champs du formulaire", "danger", 5000);
                var innerHTML = $(data).find("form[name='content']").html();
                $("form[name='content']").html(innerHTML);
            }
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on("click", ".icon-select", function (e) {
    icon = $(this).data("icon");
    html= "<span id='selected-icon' class='iconify' data-inline='false' data-width='50px' data-icon='"+icon+"''></span>"
    $("#selected-icon").html(html);
    $("#content_icon").val(icon);
    $(".icon-select").removeClass("border border-success");
    $(this).addClass("border border-success");
    $("#modalIconSelector").modal("hide");
});

$(document).on("click", ".healtInsuranceSetStatus", function (e) {
    e.preventDefault();
    target = $(this);
    $.ajax({
        type: "POST",
        url: "/admin/healthInsurance/status/" + $(this).data("id") + "/" + $(this).data("status"),
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

$(document).on("click", ".btn-edit", function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/admin/content/" + $(this).data("id") + "/axjaxContentFormCreate",
        success: function (data) {
            $("#modalContentForm").replaceWith(data);
            $("#modalContentForm").modal();
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on("click", ".btn-media-selector", function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/admin/media/" + $(this).data("mediacategory") + "/axjaxMediaSelectorCreate",
        success: function (data) {
            $("#modalMediaSelector").replaceWith(data);
            $("#modalMediaSelector").modal();
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on("submit", "form[name='define_media']", function (e) {
    e.preventDefault();
    target = $(this).data("mediacategory");
    selectedMedias = $("#modalMediaSelector .border-success").data("mediasrc");
    $(".submit-btn").html("<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>");
    $.ajax({
        type: "POST",
        url: "/admin/media/" + $(this).data("mediacategory") + "/axjaxMediaSelectorCreate",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
            $("#modalMediaSelector").modal("hide");
            $("#modalMediaSelector").html("");
            showAlert("Média modifié avec succès", "success", 5000);
            $("." + target + "Media").attr("src", selectedMedias);
            if (target == "cover") {
                $("#slider").css("background-image", "url(" + selectedMedias + ")");
            }
        },
        error: function (data) {
            showAlert("<strong>Erreur</>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on("click", ".btn-upload", function (e) {
    option = $(this).data("formtype") || "default";
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/admin/upload/" + option,
        success: function (data) {
            $("#modalUploadForm").replaceWith(data);
            $("#modalUploadForm").modal();
            $("#modalUploadForm").attr("data-option", option);
            bsCustomFileInput.init()
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on("submit", "form[name='media']", function (e) {
    e.preventDefault();
    option = $("#modalUploadForm").data("option");
    $.ajax({
        type: "POST",
        url: $(this).attr("action"),
        data: new FormData(this),
        contentType: false,
        processData: false,
        xhr: function () {
            //upload Progress
            var xhr = $.ajaxSettings.xhr();
            if (xhr.upload) {
                xhr.upload.addEventListener("progress", function (event) {
                    var percent = 0;
                    var position = event.loaded || event.position;
                    var total = event.total;
                    if (event.lengthComputable) {
                        percent = Math.ceil(position / total * 100);
                        $(".progress").removeClass("d-none");
                    }
                    if (percent == 100) {
                        $(".progress-bar").text("Envoi terminé");
                        $(".progress-bar").width(percent + "%");

                    } else {
                        $(".progress-bar").text(percent + "%");
                        $(".progress-bar").width(percent + "%");
                    }
                }, true);
            }
            return xhr;
        },//end upload progress
        success: function (data) {
            if (data["status"] === "success") {
                if (option == "mutuelle") {
                    $("#partenaireMutuelleTable tr:last").after(data["render"]);
                } else if (option == "marque") {
                    $("#brandsTable tr:last").after(data["render"]);
                } else if (option == "socialnetwork") {
                    $("#socialNetworksTable tr:last").after(data["render"]);
                }
                else {
                    $("#mediaTable tr:last").after(data["render"]);
                };
                $("#modalUploadForm").modal("hide");
                $(".progress").addClass("d-none");
                showAlert("<strong>Upload terminé</strong>, L'image a été ajoutée avec succès", "success", 5000);
            } else {
                showAlert("<strong>Echec,</strong> vérifiez les champs du formulaire", "danger", 5000);
                var innerHTML = $(data).find("form[name='media']").html();
                $("form[name='media']").html(innerHTML);
                bsCustomFileInput.init()
            }
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on("click", ".ajaxDeleteMedia", function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: $(this).attr("href"),
        success: function (data) {
            $("#mediaRow" + data).remove();
            $("#modalConfirm").modal("hide")
            showAlert("Média supprimé avec succès", "success", 5000);
        },
        error: function (data) {
            showAlert("<strong>Erreur</>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

$(document).on("click", ".mediaSingleSelector", function (e) {
    id = $(this).data("media_id");
    $(".mediaSingleSelector").removeClass("border border-success"),
        $(this).addClass("border border-success");
    $("form input").prop("checked", false);
    $("#define_media_mediaId_" + id).prop("checked", true);
});

$(document).on("click", ".mediaMultipleSelector", function (e) {
    id = $(this).data("media_id");
    $(this).toggleClass("border border-success"),
        elem = $("#define_media_mediaId_" + id);
    elem.prop("checked", !elem.prop("checked"));
});

$(document).on("click", "#healthInsuranceAdd", function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/admin/healthInsurance/add",
        success: function (data) {
            $("#modalHealthInsuranceForm").replaceWith(data);
            $("#modalHealthInsuranceForm").modal();
        },
        error: function (data) {
            showAlert("<strong>Erreur</strong>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});




//Submit health insurance form
$(document).on("submit", "form[name='health_insurance']", function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/admin/healthInsurance/add",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
            if (data["status"] === "success") {
                $("#modalHealthInsuranceForm").modal("hide");
                $("#healthInsurancesTable tr:last").after(data["render"]);
                showAlert("Mutuelle ajoutée avec succès", "success", 5000);
            } else {
                showAlert("<strong>Echec,</strong> vérifiez les champs du formulaire", "danger", 5000);
                var innerHTML = $(data).find("form[name='health_insurance']").html();
                $("form[name='health_insurance']").html(innerHTML);
            }
        },
        error: function (data) {
            showAlert("<strong>Erreur</>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});

//Delete health insurance
$(document).on("click", ".ajaxDeleteHealthInsurance", function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: $(this).attr("href"),
        success: function (data) {
            $("#healthInsuranceRow" + data).remove();
            $("#modalConfirm").modal("hide")
            showAlert("Mutuelle supprimée avec succès", "success", 5000);
        },
        error: function (data) {
            showAlert("<strong>Erreur</>, la requête n'a pu aboutir", "danger", 5000);
        }
    });
});
