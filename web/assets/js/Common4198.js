/* Make all comboboxes look fancy and remove/show the empty text */

function FileDownloadDialog(url) {
    $.fileDownload(url, {
        preparingMessageHtml: "<div class=\"dialog-message\">Please wait while generating export...</div>",
        failMessageHtml: "<div class=\"dialog-message\">There was a problem generating the export<br><br>Please try again.</div>",
        dialogOptions: { modal: true, title: "Export", "resizable": false }
    });
    return false;
}

function SetUpForm(formId, title, onSuccess, onBeforeSubmit, onBeforeSerialize, onValidationFailed, onOpen) {
    $(formId).dialog({
        modal: true,
        resizable: false,
        title: title,
        close: function (event, ui) {
            $(formId).remove();
        },
        open: function () {
            if (onOpen != undefined)
                onOpen();
        }
    });

    SetUpAjaxForm(formId, onSuccess, onBeforeSubmit, onBeforeSerialize, onValidationFailed, onOpen);
}

function SetUpAjaxForm(formId, onSuccess, onBeforeSubmit, onBeforeSerialize, onValidationFailed, onOpen) {
  
    $("input[type=submit]", formId).button();
    $("input[type=reset]", formId).button();
    $("button", formId).button();

    $("form", formId).ajaxForm({
        dataType: "json",
        success: function (response, statusText, xhr, form) {
            if (typeof response == 'string' && response.indexOf('{"Msg":"') == 0) {
                response = $.parseJSON(response);
            }
            $.unblockUI();
            if (typeof response == 'object') {
                if (response.Msg.length > 0) {
                    var errEl = $("#err");
                    $("#errMsg", errEl).html(response.Msg);
                    errEl.show();
                }
                else if (typeof (response.Warning) != "undefined") {
                    if (confirm(response.Warning.Msg)) {
                        $("#" + formId + " #WarningAccepted").attr("value", "True");
                        form.submit();
                    }
                }
                else {
                    if (typeof (response.Warnings) == "object" && response.Warnings.length) {
                        $.each(response.Warnings, function (i, n) {
                            alert(n);
                        });
                    }

                    if (onSuccess != undefined) onSuccess(response);
                    $(formId).dialog('close');
                }
            }
            else if (typeof response == 'string') {
                $(formId).dialog('close');
                if (onSuccess != undefined)
                    onSuccess(response);
            }
            else {
                $("#innerForm").replaceWith($("#innerForm", $(response)));
                $("input[type=submit]", formId).button();
                $("input[type=reset]", formId).button();
                $("button", formId).button();
            }
        },
        beforeSubmit: function (arr, form, options) {
            $("#err").hide();
            if (onBeforeSubmit != undefined && !onBeforeSubmit()) {
                return false;
            }
            else {
                var valid = $(form).valid();

                //do on validation failed actions if defined and form is not valid
                if (onValidationFailed != undefined && !valid)
                    onValidationFailed();

                return valid;
            }
        },
        beforeSerialize: function (form, options) {
            if (onBeforeSerialize != undefined)
                return onBeforeSerialize();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var errEl = $("#err");
            alert('error');
            $("#errMsg", errEl).text("Error processing data: " + XMLHttpRequest.statusText + "(" + XMLHttpRequest.responseText + ")");
            errEl.show();
        }
    });
}