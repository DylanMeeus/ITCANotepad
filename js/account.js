/**
 * Created by Dylan on 13/08/2015.
 */



function generateKey()
{
    // a function that will return an API key - this key is now associated with your account.

    console.log($("#textid").val());
    saved = true;
    $("#savedID").css('color','green');
    console.log("saving notes");
    $.ajax({
        url:"index.php?action=generateAPIkey",
        type:'GET',
        success: function(response)
        {
            var key = (response.responseText);
            // hide the button and display the key on the page.
            window.location="index.php?action=gotoaccount";
        },
        complete: function(response)
        {
            // this just gets called when the ajax call is done. It's like the finally of a try-catch.
        //    console.log(response);
        }
    })

}

function recoverPassword()
{
    console.log("recovering password");

    // First we need to get the password from the database.

    var mail = document.getElementById("recoveryMail").textContent;
    console.log(mail);


    $.ajax({
        type: "POST",
        url: "https://mandrillapp.com/api/1.0/messages/send.json",
        data: {
            "key": "jzYtZum9FGfYsYEQQZC6qg",
            'message': {
                'from_email': 'password-recovery-bot@it-ca.net',
                'to': [
                    {
                        'email': 'meeusdylan@hotmail.com',
                        'name': 'Dylan',
                        'type': 'to'
                    }
                ],
                'autotext': 'true',
                'subject': '++Notepad password recovery.',
                'html': 'You have a note open on it-ca!!'
            }
        }
    }).done(function(response) {
        console.log(response); // if you're into that sorta thing
    });
}