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

    var mail = document.getElementById("email").value;;
    console.log(mail);

    if(mail != "")
    {
        $.ajax({
            type:"GET",
            url:"index.php?action=startpasswordrecovery&email="+mail,
            success:function(result){
                $.ajax({
                    type: "POST",
                    url: "https://mandrillapp.com/api/1.0/messages/send.json",
                    data: {
                        "key": "jzYtZum9FGfYsYEQQZC6qg",
                        'message': {
                            'from_email': 'password-recovery-bot@it-ca.net',
                            'to': [
                                {
                                    'email': mail,
                                    'name': mail.substring(0,mail.indexOf("@")),
                                    'type': 'to'
                                }
                            ],
                            'autotext': 'true',
                            'subject': '++Notepad password recovery.',
                            'html': "It seems like you have lost your password to www.it-ca.net/notepad. But, fear not for help is here! Follow this link to recover your password. " +
                            "www.it-ca.net/notepad/index.php?action=gotorecoverpassword&recoveryid="+result //adding a point at the end of this will cause errors with the 'RecoveryString'
                        }
                    }
                }).done(function(response) {
                    // We can now redirect the user to the homepage.
                    window.location = "index.php?action=gotologin&notif=recoverysend";
                });

            }
        });
    }
    else
    {
        alert("field can not be empty");
    }
}