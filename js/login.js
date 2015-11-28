/**
 * Created by Dylan on 25/11/2015.
 */


/* The check actions validate a step of user-input. If validation is false -> hide button to submit */

/**
 * Check if the username is valid and available.
 * Build in some basic rules? (regex)
 * We should try to NOT flood the database. Only check the database if more than 3 characters changed?
 *
 */
var changes = 0;
function checkUsername()
{
    changes++;
    if(changes%3==0)
    {
        // Now we query the database. Or maybe use a timeout as well?
        isUniqueUsername($("#username").val());
    }
}

/**
 * This method will query the database.
 * Separated so we can also launch it when the username box loses focus.
 * so we can avoid flooding the database will calls to the usernames.
 *
 */
function isUniqueUsername(user)
{
   // alert("making the ajax call");
    // Check out the result to see if it returns true..
    $.ajax({
        url:"index.php?action=isuniqueusername",
        type:'POST',
        data : { username : user},
        success: function(text)
        {
            console.log("succes!!\n" + text);
            if(text === "true") {
                console.log("The text was true");
                $("#username").css('background-color','green');
            }else{
                console.log("The text was false");
                $("#username").css('background-color','red');
            }
        },
        complete: function(response)
        {
            console.log(response);
        }
    })
}

function noInvalidCharactersInUsername(username){
    return username.match(/[aA-zZ]/);
}

/**
 * Checks if two passwords match.
 *
 */
function passwordMatch()
{
    // without jquery here?
}

$('#mail').on('input',checkUsername());


$('#inputPassword').keyup(function(){
   console.log("key up event");
});