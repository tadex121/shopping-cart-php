/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var BASE_URL = $("#BASE_URL").val();

function user_login(Type) {

    var Email = $("#EmailLogin").val();
    var Password = $("#PasswordLogin").val();
    var S1, S2;
    if (Email == "" || !isEmail(Email)) {
        S1 = 0;
        $("#EmailLogin").addClass("requiredInput");
    } else {
        S1 = 1;
        $("#EmailLogin").removeClass("requiredInput");
    }

    if (Password == "") {
        S2 = 0;
        $("#EmailPassword").addClass("requiredInput");
    } else {
        S2 = 1;
        $("#EmailPassword").removeClass("requiredInput");
    }

    if (S1 == 1 && S2 == 1) {
        $.post(BASE_URL + "/user_login", {
            Email: Email,
            Password: Password,
        }, function (data, status) {
            if (status == "success") {
                var n = JSON.parse(data);
                var StatusText = n.StatusText;
                var Location = n.Location;
                if (Location == "") {
                    $("#ErrorLogin").html(StatusText);
                    $("#ErrorLogin").slideDown();
                } else {
                    $("#ErrorLogin").hide();
                    location.href = Location;
                }
            }
        });
    }
}

function user_register() {

    $('input.required').each(function () {
        if (!$(this).val()) {
            $(this).addClass("requiredInput");
        } else {
            $(this).removeClass("requiredInput");
        }
    });
    var Email = $("#Email").val();
    var S1;
    if (Email != '') {
        if (!isEmail(Email)) {
            $("#Email").addClass("requiredInput");
        }
    }

    var Password = $("#Password").val();
    var RepeatPasword = $("#RepeatPassword").val();
    var upperCase = new RegExp('[A-Z]');
    if (Password != RepeatPasword && Password != '' && RepeatPasword != '') {
        $("#Password").addClass("requiredInput");
        $("#RepeatPassword").addClass("requiredInput");
        $("#ErrorRegister").slideDown();
    } else {
        if (Password.length <= 6) {
            $("#Password").addClass("requiredInput");
            $("#RepeatPassword").addClass("requiredInput");
            S1 = 0;
            $("#ErrorRegister").slideDown();
        } else {
            if (Password.match(upperCase)) {
                $("#Password").removeClass("requiredInput");
                $("#RepeatPassword").removeClass("requiredInput");
                S1 = 1;
                $("#ErrorRegister").slideUp();
            } else {
                $("#Password").addClass("requiredInput");
                $("#RepeatPassword").addClass("requiredInput");
                S1 = 0;
                $("#ErrorRegister").slideDown();
            }
        }
    }

    if (!$(".requiredInput")[0] && S1 == 1) {
        $.post(BASE_URL + "/user_register", $('.register_form').serialize(), function (data, status) {
            if (status == 'success') {
                var n = JSON.parse(data);
                var StatusText = n.StatusText;
                var Location = n.Location;
                if (Location == "") {
                    $("#ErrorRegister").html(StatusText);
                    $("#ErrorRegister").slideDown();
                } else {
                    $("#ErrorRegister").hide();
                    location.href = Location;
                    console.log(Location);
                }
            }
        });
    }
}

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    email = email.replace(/ /g, '');
    return regex.test(email);
}

function addToList() {
    var ListText = $("#list").val();

    if(ListText != "") {
    $.post(BASE_URL + "/show_list", {
        ListText: ListText
      }, function(data, status) {
        if (status === 'success') {
            location.reload();
        }
      });
    }


}

function editList(ID) {
    var ListText = $("#" + ID).val();
    $.post(BASE_URL + "/edit_list", {
        ID: ID,
        ListText: ListText
      }, function(data, status) {
        if (status === 'success') {
            location.reload();
        }
      });
}

function deleteList(ID) {
    console.log(ID);
    $.post(BASE_URL + "/delete_list", {
        ID: ID
      }, function(data, status) {
        if (status === 'success') {
            location.reload();
        }
      });
}

function markCompleted(ID) {

    var isChecked = $('#checkbox-input-' + ID).is(':checked');
    var Value = isChecked ? 1 : 0;
    $.post(BASE_URL + "/mark_completed", {
        Value: Value,
        ID: ID
      }, function(data, status) {
        if (status === 'success') {
            location.reload();
        }
      });
}


