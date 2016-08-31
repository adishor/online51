$(document).ready(function () {
    if ($('#register_profile_county').val()) {
        var city = $('#register_profile_city option:selected').text();
        getLocalities('register_profile_county', 'register_profile_city', city);
    } else {
        $("#LocalitiesDiv").hide();
    }
    ShowHideItems();
    if ($("#EmployeesNr").val() != "") {
        $("#register_noEmployees option:selected").text($("#EmployeesNr").val());
    }
    $(function () {
        $('select.stareFizica, select.unitateMasura, select.stocareTip, select.tratareMod, select.tratareScop, select.transportMijloc, select.transportDestinatia, select.operatiaDeValorificare, select.operatiaDeEliminare, select.operatia').selectmenu({
            width: 500,
            menuWidth: 500
        });
    });

    $(function () {
        $('select.EGD3OperatiaValorificare, select.EGD4OperatiaEliminare').selectmenu({
            width: 300,
            menuWidth: 300
        });
    });
});

function decimalPlaces(num) {
    var match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
    if (!match) {
        return 0;
    }
    return Math.max(
            0,
            // Number of digits right of decimal point.
            (match[1] ? match[1].length : 0)
            // Adjust for scientific notation.
            - (match[2] ? + match[2] : 0));
}

function validate(evt, elem) {
    var theEvent = evt || window.event,
            key = theEvent.keyCode || theEvent.which,
            ENTER = 13,
            TAB = 9,
            BACKSPACE = 8,
            DEL = 46,
            ARROW_KEYS = {left: 37, right: 39},
    regex = /[0-9]|\./;
    if (key === ENTER) {
        var tabindex = parseInt($(elem).attr("tabindex")) + 1;
        $("input[tabindex='" + tabindex + "']").focus();
        evt.preventDefault();
        return true;
    }
    if (key === TAB || key === BACKSPACE || key === DEL || key === ARROW_KEYS.left || key === ARROW_KEYS.right) {
        return true;
    }
    key = String.fromCharCode(key);
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}

function popitup(url) {
    newwindow = window.open(url, 'name', 'height=1000,width=1200');
    if (window.focus) {
        newwindow.focus();
    }
    return false;
}

function goRegister() {
    if ($('#check').prop('checked')) {
        $("#RegisterText").hide();
    }
}

function EmployeesNrChanged() {
    $("#EmployeesNr").val($("#register_noEmployees option:selected").text());
}

function ShowHideItems() {
    if ($("#register_profile_function option:selected").text() == "Serviciu intern" ||
            $("#register_profile_function option:selected").text() == "Administrator") {
        $("#lblEmployeesNr").show();
        $("#register_profile_noEmployees").show();
        $("#lblCertificateNumber").hide();
        $("#tbCertificateNumber").hide();
        $('#tbCertificateNumber input').each(function () {
            $(this).rules("remove");
        });
        $('#tbCertificateNumber input').prop('disabled', true);
    } else {
        if ($("#register_profile_function option:selected").text() == "Lucrator desemnat") {
            $("#lblEmployeesNr").show();
            $("#register_profile_noEmployees").show();
            $("#lblCertificateNumber").hide();
            $("#tbCertificateNumber").hide();
            $('#tbCertificateNumber input').each(function () {
                $(this).rules("remove");
            });
            $('#tbCertificateNumber input').prop('disabled', true);
        } else {
            if ($("#register_profile_function option:selected").text() == "Serviciu extern") {
                $("#lblEmployeesNr").hide();
                $("#register_profile_noEmployees").hide();
                $("#lblCertificateNumber").show();
                $("#tbCertificateNumber").show();
                $('#tbCertificateNumber input').removeAttr('disabled');
                $('#tbCertificateNumber input').each(function () {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: 'Va rugam completati *Nr certificat de abilitare'
                        }
                    });
                });
            }
        }
    }
}

function SubmitRegister() {
    if ($("#ValidCui").css("display") == 'none') {
        if ($("#ValidIban").css("display") == 'none') {
            {
                $("#ChangeInfoForm").submit();
            }
        }
    }
    if ($('#check').prop('checked')) {
        $("#RegisterText").hide();

        if ($("#ValidCui").css("display") == 'none') {
            if ($("#ValidIban").css("display") == 'none') {
                {
                    $("#RegisterForm").submit();
                }
            }
        }
    } else {
        $("#RegisterText").show();
    }
}

function SubmitRegisterOrder() {
    $("#RegisterForm").submit();
}

$("#register_cui, #register_profile_cui").bind('input propertychange', function () {
    $("#validCuiCustomError").hide();
    $("#ValidCui").hide();
    var cuiText = $(this).val();

    if (cuiText.toString().toLowerCase().indexOf("ro") >= 0) {
        cuiText = cuiText.toString().toLowerCase().replace("ro", "")
    }
    if (cuiText) {
        var link = 'http://openapi.ro/api/validate/cif/' + cuiText + '.json';

        $.ajax({
            type: 'get',
            url: link,
            dataType: 'jsonp',
            success: function (data) {
                if (data["valid"] == false) {
                    $("#ValidCui").show();
                } else {
                    $("#ValidCui").hide();
                }
            },
            error: function (data) {
                alert("error");
            }
        })
    } else {
        $("#ValidCui").show();
    }
});

$("#register_iban, #register_profile_iban").bind('input propertychange', function () {
    $("#validIbanCustomError").hide();
    $("#ValidIban").hide();
    var ibanText = $(this).val();
    var link = 'http://openapi.ro/api/validate/iban/' + ibanText + '.json';

    $.ajax({
        type: 'get',
        url: link,
        dataType: 'jsonp',
        success: function (data) {
            if (data["valid"] == false) {
                $("#ValidIban").show();
            } else {
                $("#ValidIban").hide();
            }
        },
        error: function (data) {
            alert("error");
        }
    });
});

$("#register_profile_noCertifiedEmpowerment").bind('input propertychange', function () {
    $("#validNoCertifiedEmpowermentCustomError").hide();
});

function getLocalities(countyId, cityId, cityValue) {
    $.ajax({
        type: 'post',
        url: ajax_localities,
        dataType: 'json',
        data: {
            countyId: $('#' + countyId).val()
        },
        success: function (cities) {
            var msg = '';
            $.each(cities, function (index, element) {
                msg = msg + '<option value="' + index + '">' + element + '</option>';
            });

            $("#LocalitiesDiv").hide();
            if (msg !== '') {
                $("#LocalitiesDiv").show();
                msg = '<option value="">Selectati Localitatea</option>' + msg;
                $('#LocalitiesDiv select').html(msg);
                if (cityValue) {
                    $('#' + cityId + ' option').each(function () {
                        if ($(this).text() == cityValue) {
                            $(this).attr('selected', 'selected');
                        }
                    });
                }
            }
        }
    });
}


function disableCheckedCheckedbox(cb) {
    cb.val(cb.prop('checked', false));
    cb.siblings("span").addClass("pop-display");
    setTimeout(function () {
        $('span.pop').removeClass("pop-display");
    }, 5000);
}

function isCheckedx(cb, id, no, isRegister) {
    var checkedOffice = $("#" + id + " input:checked").length;
    if (isRegister) {
        var value = $(cb).attr("name").replace("domains", "").replace("[", "").replace("]", "");
        if ($(cb).prop('checked')) {
            $("#register_order_type_registerDomainIds").val($("#register_order_type_registerDomainIds").val() + "," + value);
        } else {
            $("#register_order_type_registerDomainIds").val($("#register_order_type_registerDomainIds").val().replace("," + value, ""));
        }
    }
    if (checkedOffice > no) {
        disableCheckedCheckedbox(cb);
        if (isRegister) {
            $("#register_order_type_registerDomainIds").val($("#register_order_type_registerDomainIds").val().replace("," + value, ""));
        }
    }
}

function areAllChecked(id, max) {
    var checked = $("#" + id + " input:checked").length;
    if (checked === max) {
        return true;
    }
}

function popitup(url) {
    newwindow = window.open(url, 'name', 'height=1000,width=1000');
    if (window.focus) {
        newwindow.focus()
    }
    return false;
}

function popitupPassword(url) {
    newwindow = window.open(url, 'name', 'height=410,width=500');
    if (window.focus) {
        newwindow.focus()
    }
    return false;
}

function Register() {
    $("#validUser").hide();
    $("#validPass").hide();
    window.location.href = 'Account/Register.html';
}

function ResetPasswordShow() {
    $("#freeAccountDiv").hide();

    $("#resetPassDiv").show();
    $("#resetPass").show();
    $("#resetPassSucc").hide();

    $("#UserNamePass").val("");
    $("#UserNameValid").hide();
    $("#UserNameValidEmail").hide();
}

function ResetPasswordSucceeded() {
    $("#resetPassDiv").hide();
}

function ResendActivationSucceeded() {
    $("#resendActivationEmailDiv").hide();
}

function SubmitResetPassword(url) {
    $("#UserNameValid").hide();
    $("#UserNameValidEmail").hide();

    var isValid = true;
    var emailText = $("#UserNamePass").val();

    if (emailText == "") {
        $("#UserNameValid").show();
        isValid = false;
    } else {
        if (!ValidateEmail(emailText)) {
            $("#UserNameValidEmail").show();
            isValid = false;
        }
    }

    if (isValid) {
        $.ajax({
            type: "POST",
            url: url,
            data: {email: emailText},
            dataType: 'json',
            success: function (response) {
                if (response.Msg) {
                    $("#UserNameTextError").text(response.Msg);
                    $("#UserNameTextError").show();
                } else {
                    $("#resetPass").hide();
                    $("#resetPassSucc").show();
                }
            }
        })
    }
}

function ValidateEmail(email) {
    var re = new RegExp(/.+@.+..+/);
    return re.test(email);
}

function GoLogin(url, home, admin) {
    $("#validUser").show();
    $("#validPass").show();
    $("#LoginUserName").valid();
    $("#LoginPassword").valid();
    if ($("#LoginUserName").valid() && $("#LoginPassword").valid()) {
        $.ajax({
            type: "POST",
            url: url,
            data: {'_username': $("#LoginUserName").val(), '_password': $("#LoginPassword").val()},
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    if (response.admin) {
                        window.location.href = admin;
                    } else {
                        window.location.href = home;
                    }
                } else {
                    if (response.message === "User account is disabled.") {
                        $("#resendActivationEmailDiv").show();
                        $("#activationEmail").val($("#LoginUserName").val());
                    } else {
                        $("#loginValidation").show();
                        $("#resendActivationEmailDiv").hide();
                    }
                }

            }
        })
    }
}

function SubmitResendActivationEmail(url) {

    var emailText = $("#activationEmail").val();

    $.ajax({
        type: "POST",
        url: url,
        data: {email: emailText},
        dataType: 'json',
        success: function (response) {
            if (response.Msg) {
                $("#activationEmailError").text(response.Msg);
                $("#activationEmailError").show();
            } else {
                $("#resendActivationEmail").hide();
                $("#resendActivationEmailSucc").show();
            }
        }
    });
}

$("#LoginUserName").bind('input propertychange', function () {
    $("#loginValidation").hide();
});

$("#LoginPassword").bind('input propertychange', function () {
    $("#loginValidation").hide();
});

$(document).ready(function () {
    $("#LoginUserName").clearFields();
    $("#LoginPassword").clearFields();


    $('a').click(function () {
        this.blur();
    });

    $("#freeAccountDiv").keyup(function (e) {
        $("#validUser").hide();
        $("#validPass").hide();
        if (e.keyCode == 13 || e.which == 13) {
            SubmitFreeAccount();
        }
    })

    $("#resetPassDiv").keyup(function (e) {
        $("#validUser").hide();
        $("#validPass").hide();
        if (e.keyCode == 13 || e.which == 13) {
            SubmitResetPassword();
        }
    })

    $("#loginDiv").keyup(function (e) {
        if (e.keyCode == 13 || e.which == 13) {
            GoLogin();
        }
    })

});

function ReloadPage() {
    window.top.location.reload();
}

function UserLogOut(link) {
    window.location.href = link;
}

function DocumentConfirmPopup(message, path, documentId, downloadPath) {

    $('#documentConfirmModalBody').text(message);
    $('#documentConfirmModal').modal();
    $('#documentConfirmModalPath').val(path);
    $('#documentConfirmModalId').val(documentId);
    $('#documentConfirmModalDownload').val(downloadPath);
}

$('#documentConfirmModalYes').click(function () {
    var path = $('#documentConfirmModalPath').val();
    var documentId = $('#documentConfirmModalId').val();
    var downloadPath = $('#documentConfirmModalDownload').val();
    $.ajax({
        type: "POST",
        url: path,
        data: {documentId: documentId},
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                window.location.href = downloadPath;
                $('#downloadLink' + documentId).attr("href", downloadPath).attr("onclick", "");
                $('#totalUserCredits').text(response.credits);
                $('#errorOrSuccess' + documentId).addClass('color-green');
                $('#documentIcon' + documentId).removeClass('color-red').addClass('color-green').text('0');
            } else {
                $('#errorOrSuccess' + documentId).addClass('color-red');
            }
            $('#errorOrSuccess' + documentId).text(response.message);
            $('#errorOrSuccess' + documentId).show();
            $('#documentConfirmModal').modal('toggle');
        }
    });
});

function VideoConfirmPopup(message, path, videoId, videoPath, videoDownload, youtubeId, flowplayerPath) {
    $('#videoConfirmModalBody').text(message);
    $('#videoConfirmModal').modal();
    $('#videoConfirmModalPath').val(path);
    $('#videoConfirmModalId').val(videoId);
    $('#videoConfirmModalVideoPath').val(videoPath);
    $('#videoConfirmModalVideoDownload').val(videoDownload);
    $('#videoConfirmModalYoutube').val(youtubeId);
    $('#videoConfirmModalFlowplayer').val(flowplayerPath);
}

$('#videoConfirmModalYes').click(function () {
    var path = $('#videoConfirmModalPath').val();
    var videoId = $('#videoConfirmModalId').val();
    var videoPath = $('#videoConfirmModalVideoPath').val();
    var videoDownload = $('#videoConfirmModalVideoDownload').val();
    var youtubeId = $('#videoConfirmModalYoutube').val();
    var flowplayerPath = $('#videoConfirmModalFlowplayer').val();
    $.ajax({
        type: "POST",
        url: path,
        data: {videoId: videoId},
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                //flowplayer
//                var html = '<div class="modal fade bs-modal-sm m-video" id="showVideoModal' + videoId + '" tabindex="-1" role="dialog"><div class="modal-dialog  modal-sm" role="video"><div class="modal-content"><div class="modal-body"><video width="480" height="320" controls><source src="' + videoPath + '" type="video/mp4" /><object id="flowplayer" data="' + flowplayerPath + '" type="application/x-shockwave-flash" width="480" height="320" ><param name="movie" value="' + flowplayerPath + '"><param name="allowfullscreen" value="true"><param name="flashvars" value="config={\'clip\':{\'url\':\'' + videoPath + '\',\'autoPlay\':false}}"></object></video></div></div></div></div>';
                if (youtubeId) {
                    var html = '<div class="modal fade bs-modal-sm m-video" id="showVideoModal' + videoId + '" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog  modal-sm" role="video"><div class="modal-content"><div class="modal-body"><iframe width="480" height="270" src="https://www.youtube.com/embed/' + youtubeId + '?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>' + (videoDownload ? '<a href="' + videoDownload + '">Descarca video</a>' : '') + '</div></div></div></div>';
                    $('#showLink' + videoId).parent().append(html);
                }

                $('#showLink' + videoId).attr("href", "javascript:;").attr("onclick", "showVideoModal" + videoId + "();");
                $('#totalUserCredits').text(response.credits);
                $('#errorOrSuccessVideo' + videoId).addClass('color-green');
                $('#documentIcon' + videoId).removeClass('color-red').addClass('color-green').text('0');
            } else {
                $('#errorOrSuccessVideo' + videoId).addClass('color-red');
            }
            $('#errorOrSuccessVideo' + videoId).text(response.message);
            $('#errorOrSuccessVideo' + videoId).show();
            $('#videoConfirmModal').modal('toggle');
        }
    });
});

$('#subscriptionAddConfirmModalYes').click(function () {
    var formId = $('#subscriptionAddConfirmModalId').val();
    $(formId).submit();
    $('#subscriptionAddConfirmModal').modal('toggle');
});

function alertNotLoggedIn(message) {
    window.alert(message);
}

function removeOrder(path) {
    $('#orderRemoveConfirmModal').modal();
    $('#orderRemoveConfirmModalPath').val(path);
}

$('#orderRemoveConfirmModalYes').click(function () {
    var path = $('#orderRemoveConfirmModalPath').val();
    window.location.href = path;
    $('#orderRemoveConfirmModal').modal('toggle');
});

$('.show-form-config').click(function () {
    $(this).parent().siblings('.form-config').toggle();
});

$(function () {
    $('select.selectmenu').selectmenu({
        width: 550,
        menuWidth: 550
    });
});

function FreeAccountRegister() {
    $("#resetPassDiv").hide();
    $("#FreeAccountExist").hide();

    $("#FreeUserNameDuplicateEmail").text("");
    $("#FreeManagerNameValid").hide();
    $("#FreeManagerName").val("");
    $("#FreeUserNameValid").hide();
    $("#FreeUserNameValidEmail").hide();
    $("#FreeUserName").val("");
    $('#freecheck').removeAttr('checked');
    $("#FreeAccountText").hide();

    $("#createFreeAccount").show();
    $("#freeAccountDiv").show();
}

function FreeAccountSucceeded() {
    $("#freeAccountDiv").hide();
}

function goFreeAccount() {
    if ($('#freecheck').prop('checked')) {
        $("#FreeAccountText").hide();
    }
}

function SubmitFreeAccount(url) {

    $("#FreeManagerNameValid").hide();
    $("#FreeUserNameValid").hide();
    $("#FreeUserNameValidEmail").hide();

    var isValid = true;
    if ($('#freecheck').prop('checked')) {
        $("#FreeAccountText").hide();

        var name = $("#FreeManagerName").val();
        var email = $("#FreeUserName").val();
        var domainSlug = '';
        if ($("#domainSlug").is('select')) {
            domainSlug = $("#domainSlug option:selected").val();
        } else {
            domainSlug = $("#domainSlug").val();
        }
        if (name == "") {
            $("#FreeManagerNameValid").show();
            isValid = false;
        }

        if (email == "") {
            $("#FreeUserNameValid").show();
            isValid = false;
        } else {
            if (!ValidateEmail(email)) {
                $("#FreeUserNameValidEmail").show();
                isValid = false;
            }
        }

        if (isValid) {
            $.ajax({
                type: "POST",
                url: url,
                data: {name: name, email: email, domainSlug: domainSlug},
                dataType: 'json',
                success: function (response) {
                    if (response.Msg) {
                        $("#FreeUserNameDuplicateEmail").text(response.Msg);
                        $("#FreeUserNameDuplicateEmail").show();
                    } else {
                        $("#createFreeAccount").hide();
                        $("#FreeAccountExist").show();
                    }
                }
            });
        }
    } else {
        $("#FreeAccountText").show();
    }
}

$("#FreeManagerName").bind('input propertychange', function () {
    $("#validUser").hide();
    $("#validPass").hide();

    $("#FreeUserNameDuplicateEmail").hide();
    $("#FreeManagerNameValid").hide();

    if ($("#FreeManagerName").val() == "") {
        $("#FreeManagerNameValid").show();
    }
});

$("#FreeUserName").bind('input propertychange', function () {
    $("#validUser").hide();
    $("#validPass").hide();

    $("#FreeUserNameDuplicateEmail").hide();
    $("#FreeUserNameValid").hide();

    $("#FreeUserNameValidEmail").hide();

    var emailText = $("#FreeUserName").val();

    if (emailText == "") {
        $("#FreeUserNameValid").show();
    } else {
        if (!ValidateEmail(emailText)) {
            $("#FreeUserNameValidEmail").show();
        }
    }
});

$(document).ajaxStart(function () {
    $("#loading").show();
});

$(document).ajaxStop(function () {
    $("#loading").hide();
});



// Js not currently used.
//function GetProfile(currentUserId) {
//    var link = '/Aldex/User/UserDetails/1';
//    link = link.replace("1", currentUserId);
//    window.open(link, "_self");
//}
//
//$("#UserNamePass").bind('input propertychange', function () {
//    $("#validUser").hide();
//    $("#validPass").hide();
//
//    $("#UserNameValid").hide();
//    $("#UserNameValidEmail").hide();
//
//    var emailText = $("#UserNamePass").val();
//    if (emailText == "") {
//        $("#UserNameValid").show();
//    } else {
//        if (!ValidateEmail(emailText)) {
//            $("#UserNameValidEmail").show();
//        }
//    }
//});

