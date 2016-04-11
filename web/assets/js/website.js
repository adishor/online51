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

function ResetPasswordShow() {
    $("#freeAccountDiv").hide();

    $("#resetPassDiv").show();
    $("#resetPass").show();
    $("#resetPassSucc").hide();

    $("#UserNamePass").val("");
    $("#UserNameValid").hide();
    $("#UserNameValidEmail").hide();
}

function FreeAccountSucceeded() {
    $("#freeAccountDiv").hide();
}

function ResetPasswordSucceeded() {
    $("#resetPassDiv").hide();
}

function goFreeAccount() {
    if ($('#freecheck').prop('checked')) {
        $("#FreeAccountText").hide();
    }
}

function SubmitFreeAccount() {

    $("#FreeManagerNameValid").hide();
    $("#FreeUserNameValid").hide();
    $("#FreeUserNameValidEmail").hide();

    var isValid = true;
    if ($('#freecheck').prop('checked')) {
        $("#FreeAccountText").hide();

        var managerText = $("#FreeManagerName").val();
        var emailText = $("#FreeUserName").val();

        if (managerText == "") {
            $("#FreeManagerNameValid").show();
            isValid = false;
        }

        if (emailText == "") {
            $("#FreeUserNameValid").show();
            isValid = false;
        } else {
            if (!ValidateEmail(emailText)) {
                $("#FreeUserNameValidEmail").show();
                isValid = false;
            }
        }

        if (isValid) {
            $.ajax({
                type: "POST",
                url: '/Aldex/Account/CreateFreeAccountPost',
                data: {managerName: managerText, email: emailText},
                success: function (response) {
                    if (response.Msg != "") {
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
                    console.log(1);
                }
            }
        })
    }
}

function ValidateEmail(email) {
    var re = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return re.test(email);
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

$("#UserNamePass").bind('input propertychange', function () {
    $("#validUser").hide();
    $("#validPass").hide();

    $("#UserNameValid").hide();
    $("#UserNameValidEmail").hide();

    var emailText = $("#UserNamePass").val();
    if (emailText == "") {
        $("#UserNameValid").show();
    } else {
        if (!ValidateEmail(emailText)) {
            $("#UserNameValidEmail").show();
        }
    }
});

function GoLogin(url, home) {
    $("#validUser").show();
    $("#validPass").show();
    $("#LoginUserName").valid();
    $("#LoginPassword").valid();
    if ($("#LoginUserName").valid() && $("#LoginPassword").valid()) {
        $.ajax({
            type: "POST",
            url: url,
            data: {'_username': $("#LoginUserName").val(), '_password': $("#LoginPassword").val()},
            success: function (response) {
                if (response.success) {
                    window.location.href = home;
                } else {
                    $("#loginValidation").show();
                }

            }
        })
    }
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

function GetProfile(currentUserId) {
    var link = '/Aldex/User/UserDetails/1';
    link = link.replace("1", currentUserId);
    window.open(link, "_self");
}