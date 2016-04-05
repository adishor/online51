$(document).ready(function () {
    if ($('#register_county').val()) {
      var city = $('#register_city option:selected').text();
      getLocalities(city);
    }
    else {
      $("#LocalitiesDiv").hide();
    }
    ShowHideItems();
    if ($("#EmployeesNr").val() != "") {
        $("#register_noEmployees option:selected").text($("#EmployeesNr").val());
    }
});

function popitup(url) {
    newwindow = window.open(url, 'name', 'height=1000,width=1200');
    if (window.focus) { newwindow.focus() }
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
    }
    else {
        if ($("#register_function option:selected").text() == "Lucrator desemnat") {
            $("#lblEmployeesNr").show();
            $("#register_noEmployees").show();
            $("#lblCertificateNumber").hide();
            $("#tbCertificateNumber").hide();
        }
        else {
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
    }
    else {
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
              }
              else {
                  $("#ValidCui").hide();
              }
          },
          error: function (data) {
              alert("error");
          }
      })
    }
    else {
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
            }
            else {
                $("#ValidIban").hide();
            }
        },
        error: function (data) {
            alert("error");
        }
    })
});

function getLocalities(city)
{
  $.ajax({
    type: 'post',
    url: ajax_localities,
    dataType: 'json',
    data: {
      countyId: $('#register_county').val()
    },
    success: function (cities) {
      var msg = '';
      $.each(cities, function(index, element) {
        msg = msg + '<option value="' + index + '">' + element + '</option>';
      });

      $("#LocalitiesDiv").hide();
      if (msg !=='') {
        $("#LocalitiesDiv").show();
        msg = '<option value="">Selectati Localitatea</option>' + msg;
        $('#LocalitiesDiv select').html(msg);
        if (city) {
          $('#register_city option').each(function(){
            if ($(this).text() == city) {
              $(this).attr('selected', 'selected');
            }
          });
        }
      }
    }
  });
}

