$(document).ready(function () {
    if ($('#register_county').val()) {
        var city = $('#register_city option:selected').text();
        getLocalities(city);
    } else {
        $("#LocalitiesDiv").hide();
    }
    ShowHideItems();
    if ($("#EmployeesNr").val() != "") {
        $("#register_noEmployees option:selected").text($("#EmployeesNr").val());
    }
    $(function () {
        $('select.EDG2StocareTip, select.EDG2TratareMod, select.EDG2TransportMijloc, select.EDG2TransportDestinatia').selectmenu({
            width: 48,
            menuWidth: 200
        });
    });

    $(function () {
        $('select.EDG2TratareScop').selectmenu({
            width: 40,
            menuWidth: 180
        });
    });

    $(function () {
        $('select.EGD3OperatiaValorificare, select.EGD4OperatiaEliminare').selectmenu({
            width: 300,
            menuWidth: 300
        });
    });
});

function popitup(url) {
    newwindow = window.open(url, 'name', 'height=1000,width=1200');
    if (window.focus) {
        newwindow.focus()
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
    if ($("#register_function option:selected").text() == "Serviciu intern" || $("#register_function option:selected").text() == "Administrator") {
        $("#lblEmployeesNr").show();
        $("#register_noEmployees").show();
        $("#lblCertificateNumber").show();
        $("#tbCertificateNumber").show();
    } else {
        if ($("#register_function option:selected").text() == "Lucrator desemnat") {
            $("#lblEmployeesNr").show();
            $("#register_noEmployees").show();
            $("#lblCertificateNumber").hide();
            $("#tbCertificateNumber").hide();
        } else {
            if ($("#register_function option:selected").text() == "Serviciu extern") {
                $("#lblEmployeesNr").hide();
                $("#register_noEmployees").hide();
                $("#lblCertificateNumber").show();
                $("#tbCertificateNumber").show();
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

$("#register_cui").bind('input propertychange', function () {
    $("#ValidCui").hide();
    var cuiText = $("#register_cui").val();

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

$("#register_iban").bind('input propertychange', function () {
    $("#ValidIban").hide();
    var ibanText = $("#register_iban").val();
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
    })
});

function getLocalities(city) {
    $.ajax({
        type: 'post',
        url: ajax_localities,
        dataType: 'json',
        data: {
            countyId: $('#register_county').val()
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
                if (city) {
                    $('#register_city option').each(function () {
                        if ($(this).text() == city) {
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

function isCheckedx(cb, id, no) {
    var checkedOffice = $("#" + id + " input:checked").length;
    if (checkedOffice > no) {
        disableCheckedCheckedbox(cb);
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

function VideoConfirmPopup(message, path, videoId, videoPath) {
    $('#videoConfirmModalBody').text(message);
    $('#videoConfirmModal').modal();
    $('#videoConfirmModalPath').val(path);
    $('#videoConfirmModalId').val(videoId);
    $('#videoConfirmModalDownload').val(videoPath);
}

$('#videoConfirmModalYes').click(function () {
    var path = $('#videoConfirmModalPath').val();
    var videoId = $('#videoConfirmModalId').val();
    var videoPath = $('#videoConfirmModalDownload').val();
    $.ajax({
        type: "POST",
        url: path,
        data: {videoId: videoId},
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                var html = '<div class="modal fade bs-modal-sm m-video" id="showVideoModal' + videoId + '" tabindex="-1" role="dialog"><div class="modal-dialog  modal-sm" role="video"><div class="modal-content"><div class="modal-body"><video width="480" height="320" controls><source src="' + videoPath + '" type="video/mp4" /><object id="flowplayer" data="flowplayer-3.2.2.swf" type="application/x-shockwave-flash" width="480" height="320" ><param name="movie" value="flowplayer-3.2.2.swf"><param name="allowfullscreen" value="true"><param name="flashvars" value="config={\'clip\':{\'url\':\'' + videoPath + '\',\'autoPlay\':false}}"></object></video></div></div></div></div>';
                
                $('#showLink' + videoId).attr("href", "javascript:;").attr("onclick", "showVideoModal" + videoId + "();");
                $('#showLink' + videoId).append(html);
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
            console.log(1);
            domainSlug = $("#domainSlug option:selected").val();
        } else {
            domainSlug = $("#domainSlug").val();
        }
        console.log(domainSlug);
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
            })
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

