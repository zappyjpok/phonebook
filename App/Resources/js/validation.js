/**
 * Created by thuyshawn on 8/08/2015.
 */

$(document).ready(function(){

    $('.validation-form').submit(function () {
        restart();
        var fields = [];
        var passed = [];
        var abort = false;
        $('.validation-form input').each(function(index, element){
            if(element.name != '_token' && element.name != 'Search' && element.name != '') {
                fields.push(element.name);
            } // end if
        }); // end each form value
        $.each(fields, function(key, value){
            //get what needs to be checked
            var checks = getChecks(value);
            //run validation
            passed.push(validations(checks, 'input[name=' + value + ']'));
        });
        // check if any values failed to abort the submit
        $.each(passed, function(key, value){
            if (value === false) {
                abort = true;
                return false;
            } // end if
        }) // go through each required value
        if(abort) { return false; } else { return true;}
    }) // on submit

    /**
     * When the user clicks away from a field, then Jquery will check if the user entered
     * the correct information.  The blur function will first check what is to be tested from
     * the getAllChecks functions.  It will then  run the validation function which will run
     * the specific checks for each field.
     */

    $('.validation-form input').blur(function (){
        restart();

        var name = $(this).prop('name');
        var selector = 'input[name=' + name + ']';
        console.log(selector);

        //find what values to check
        var checks = getChecks(name);

        // run the validation
        var passed = validations(checks, selector);

        //If checks fail focus on
        if(passed === false){
            notCorrect(selector);
        } else {
            correct(selector);
        }
    });

    /**
     * gets the checks for a specific check
     *
     */
    function getChecks(name) {
        var allValidation = getAllChecks();
        var check = [];
        $.each(allValidation, function(key, value){
            if(name === key) {
                $.each(value, function(key, value){
                    $.each(value, function(key, value){

                        check[key] = value;
                    });// end foreach get test values
                });// end sub foreach key is values
            }
        }); // end main foreach
        return check;
    }

    /**
     * Stores all checks
     */
    function getAllChecks() {
        var validation = {
            // validation checks for the user page
            name: {values: {notEmpty: false, longEnough: false, min: 4, usernameAvailable: false}},
            email: {values: {notEmpty: false, email: false, emailAvailable: false}},
            password: {values: {notEmpty: false, longEnough: false, min: 6}},
            password_confirmation: {values: {notEmpty: false, match: false, matchValue: '#password'}},
            first_name: {values: {notEmpty: false, lettersOnly: false}},
            last_name: {values: {notEmpty: false, lettersOnly: false}},
            phone: {values: {notEmpty: false, numbersOnly: false}},
            address : {values: {notEmpty: false }},
            state: {values: {notEmpty: false, lettersOnly: false }},
            city : {values: {notEmpty: false, lettersOnly: false }},
            postal_code : {values: {notEmpty: false, numbersOnly: false }}
        };
        // end object
        return validation;
    }

}); // document ready

/**
 * This validation goes through every check object to determine if the value is true.  If it is true then
 * the function will move to the next check until the end where the correct function is called.
 *
 * If the value is falsethe function terminates
 *
 * @param check
 * @param selector
 * @return Bool -- this value tells if tests were passed
 */
function validations(check, selector) {
    if(typeof check.notEmpty !== 'undefined') {
        // check if the value is empty
        check.notEmpty = checkIfEmpty(selector, 'This cannot be blank!');
        if(check.notEmpty === false) {return false; }

    }
    if(typeof check.longEnough !== 'undefined' && typeof check.min !== 'undefined') {
        // check if the value is long enough
        var message = "This must be at least " + check.min + " characters long";
        check.longEnough = checkIfLongEnough(selector, message, check.min);
        if(check.longEnough === false ) {return false;}

    }
    if(typeof check.lettersOnly !== 'undefined') {
        check.lettersOnly = lettersOnly(selector);
        if(check.lettersOnly === false ) {return false;}
    }
    if(typeof check.numbersOnly !== 'undefined') {
        check.numbersOnly = numbersOnly(selector);
        if(check.numbersOnly === false ) {return false;}
    }
    if(typeof check.match !== 'undefined' && typeof check.matchValue !== 'undefined') {
        check.match = compareValues(selector, check.matchValue, 'Your passwords must match');
        if(check.match === false ) {return false;}
    }
    if(typeof check.email !== 'undefined') {
        check.email = checkIfEmail(selector);
        if(check.email === false ) {return false;}
    }
    if(typeof check.usernameAvailable !== 'undefined') {
        check.usernameAvailable = checkUsername(selector);
        if(check.usernameAvailable === false ) {return false;}
    }
    if(typeof check.emailAvailable !== 'undefined') {
        check.emailAvailable = checkEmail(selector);
        if(check.emailAvailable === false ) {return false;}
    }
    correct(selector);
    return true;
}

/**
 * A list of functions that call different types of values
 * Each one will be called when the user leaves the field and on submit
 *
 */
// checks user name

// removes all previous erros
function restart(){
    // remove past error messages
    $('div.error').remove();
    $('div.ui-effects-wrapper').remove();
}

// css for when validation is passed
function correct(value)
{
    // make sure the submit button is not affected
    if(value != 'input[name=Submit]'){
        $(value).css('background-color', '#D1FFC2');
    }
}

// css for when validation fails
function notCorrect(value)
{
    $(value).css('background-color', '#FFB2B2');
    $('.error').effect('shake', {times: 3, distance: 5});
    $(value).focus();
}

// creates a message to display to the user
function createMessage(message) {
    var validationMessage = '<div class="error">';
    validationMessage += message;
    validationMessage += '</div>';
    return validationMessage;
}

// Returns a boolean if the value is empty with a message
function checkIfEmpty(selector, message) {
    value = $(selector).val().trim();
    var validationMessage = createMessage(message);

    if (value === '') {
        $(selector).before(validationMessage);
        notCorrect(selector);
        return false;
    } else {
        return true;
    } // end if empty
}

// Returns a boolean if the value is not long enough
function checkIfLongEnough(selector, message, number) {
    value = $(selector).val().trim();
    var validationMessage = createMessage(message);

    if(value.length < number) {
        $(selector).before(validationMessage);
        notCorrect(selector);
        return false;
    } else {
        return true;
    } // end long enough if
}

//Returns a boolean if the value is not an email
function checkIfEmail(selector) {
    var email = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i;
    // check if the value is an email
    return checkRegularExpression(selector, 'Please enter a valid email address', email);
}

//Returns a boolean if there is anything other than letters in the value
function lettersOnly(selector) {
    var checkValue = /^[a-zA-Z]*$/
    // check if the value contains numbers only
    return checkRegularExpression(selector, 'This is not valid', checkValue);
}

//Returns a boolean if there is anything other than numbers in the value
function numbersOnly(selector) {
    var checkValue = /^[0-9]*$/
    // check if the value contains numbers only
    return checkRegularExpression(selector, 'This is not valid', checkValue);
}

// Returns a boolean if a value passed a regular expression
function checkRegularExpression(selector, message, regularExpression) {
    value = $(selector).val().trim();
    var validationMessage = createMessage(message);

    if(!regularExpression.test(value)) {
        $(selector).before(validationMessage);
        notCorrect(selector);
        return false;
    } else {
        return true;
    }
}

// Compares to values and returns a boolean
function compareValues(selector1, selector2, message) {
    var validationMessage = createMessage(message);
    var value1 = $(selector1).val().trim();
    var value2 = $(selector2).val().trim();

    if(value1 === value2) {
        return true
    } else {
        $(selector1).before(validationMessage);
        notCorrect(selector1);
        return false
    }
}

// Ajax request to see if the username is available
function checkUsername(selector){
    value = $(selector).val().trim();
    var url = "/TechReader/public/register/check/username/" + value;
    //var url = "/register/check/username/" + value;

    // Ajax request to see if the username has been taken
    var result = ajaxUsernameRequest(url);
    if(result === false) {
        $(selector).before('<div class="error"> This username has already been taken </div>');
        notCorrect(selector);
    }

    return result;
}

// Ajax request to see if the email is available
function checkEmail(selector){
    value = $(selector).val().trim();
    var url = "/TechReader/public/register/check/email/" + value;
    //var url = "/register/check/email/" + value;

    // Ajax request to see if the username has been taken
    var result = ajaxUsernameRequest(url);
    if(result === false) {
        $(selector).before('<div class="error"> This email is already registered </div>');
        notCorrect(selector);
    }
    return result;
}

// Returns the ajax value
function ajaxUsernameRequest(url){
    var jqxhr = $.ajax({
        async : false,
        type : 'GET',
        url: url,
        dataType : 'JSON'
    }).responseJSON;
    return jqxhr.result;
}