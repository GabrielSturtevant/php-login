/**
 * Created by gabriel on 11/25/16.
 */

var disable = [0,0];

function contains_special_characters(strng) {
    str = strng.value.toString();
    var special_chars = ['!','\"','#','$','%','&','(',')','*','+',',','-','.','/',':',';','<','=','>','?','@','[','\\',
            '\'',']','^','_','`','{','|','}','~',];
    for(var i = 0; i < special_chars.length; i++){
        if(str.indexOf(special_chars[i]) >= 0)
            return true;
    }
    return false;
}
function contains_numbers(strng) {
    str = strng.value.toString();
    var numbers = ['1','2','3','4','5','6','7','8','9','0'];
    for(var i = 0; i < numbers.length; i++){
        if(str.indexOf(numbers[i]) >= 0)
            return true;
    }
    return false;
}
function password_criteria(){

    var pass = document.getElementById("password");
    if(pass.value.length < 8){
        document.getElementById("password-warnings").className = "alert alert-warning";
        document.getElementById("password-warnings").innerHTML =
            "<strong>Warning</strong>Your password must have at least 8 characters";
        disable[1] = 0;
        enable_button();
    }else if(pass.value.toLowerCase() == pass.value ){
        document.getElementById("password-warnings").className = "alert alert-warning";
        document.getElementById("password-warnings").innerHTML =
            "<strong>Warning</strong>Password Must contain at least one uppercase letter";
        disable[1] = 0;
        enable_button();
    } else if(!contains_special_characters(pass)) {
        document.getElementById("password-warnings").className = "alert alert-warning";
        document.getElementById("password-warnings").innerHTML =
            "<strong>Warning</strong>Your password Must contain at least one special character";
        disable[1] = 0;
        enable_button();
    }else if(!contains_numbers(pass)) {
        document.getElementById("password-warnings").className = "alert alert-warning";
        document.getElementById("password-warnings").innerHTML =
            "<strong>Warning</strong>Your password Must contain at least one number";
        disable[1] = 0;
        enable_button();
    }else {
        document.getElementById("password-warnings").className = "alert alert-success";
        document.getElementById("password-warnings").innerHTML =
            "<strong>Success</strong>Your password meets all requirements";
        disable[0] = 1;
        enable_button();
    }
}
function password_match() {
    var pass1 = document.getElementById("password");
    var pass2 = document.getElementById("password-dup")
    if ( pass1.value.toString() == pass2.value.toString()){
        document.getElementById("passwords-match").className="alert alert-success";
        document.getElementById("passwords-match").innerHTML =
            "<strong>Success</strong>Your passwords match";
        disable[1] = 1;
        enable_button()
    } else {
        document.getElementById("passwords-match").className="alert alert-warning";
        document.getElementById("passwords-match").innerHTML =
            "<strong>Warning</strong>Your passwords do not match";
        disable[1] = 0;
        enable_button();
    }
}

function enable_button() {
    if(disable[0] == 1 && disable[1] == 1){
        document.getElementById("create").disabled = false;
    } else {
        document.getElementById("create").disabled = true;
    }
}
