/**
 * Created by Dylan on 1/08/2015.
 */



var saved = true;
var sharedUsers = 0;
function setupNoteDetailPage()
{

    var x = document.getElementById("newlinkdiv");
 //   x.style.visibility="hidden";
    $("#savedID").css('color','green');
    $("#textid").keyup( function (){
        saved = false;
        $("#savedID").css('color','red');
    });

    $("#colourid").val($("#originalColour").val());
    // before we bounce - we check the stuff
    if($("#textid").val() == ""){
        doBounce($("#containerdiv"), 3, '10px', 300);
    }
    saveNotes();
}


function doBounce(element, times, distance, speed) {
    for(i = 0; i < times; i++) {
        element.animate({marginTop: '-='+distance},speed)
            .animate({marginTop: '+='+distance},speed);
    }
}

function setupPage()
{
    document.getElementById("newnotediv").style.visibility="hidden";

    $("#notelist").hide().fadeIn(1500);
    // apply fade-in to the other div?
    noteLookup();
}

function addUser(){
    ++sharedUsers;
    var txt = $("<input/>");
    txt.attr("type", "text");
    txt.attr("id", "user" + sharedUsers);
    txt.attr("name", "user" + sharedUsers);
    $("#users").append(txt);
}


function saveLink()
{

    console.log("saving notes");
    console.log("Changing the link to actually be correct");

    var correctLink = "";

    if($("#linkurl").val().substr(0,4)!=="http")
    {
        correctLink = "http://"+$("#linkurl").val();
    }
    else
    {
        correctLink = $("#linkurl").val();
    }
    console.log(correctLink);

    var correctName = $("#linkname").val();
    if(correctLink !== "" && $("#linkname").val()!=="")
    {
        $("#linkname").val("");
        $("#linkurl").val("");
        $.ajax({
            url:"index.php?action=savelink",
            type:'POST',
            data : {noteid:$("#noteID").val(),linkUrl :correctLink, linkName : correctName},
            success: function()
            {
                var link = $('<a>', {
                    text : correctName,
                    href : correctLink,
                    class : "linkstyle",
                    target:"_blank"
                }).appendTo($("#oldlinks"));

                $('</br>').appendTo($("#oldlinks"));
            },
            complete: function(response)
            {

            }
        })
    }
}

function deleteLink(id)
{
    $.ajax({
        url:"index.php?action=deletelink",
        type:'POST',
        data : {linkid:id},
        success: function()
        {
            // remove this paragraph & button?
            $("#linkno"+id).remove();
            $("#buttonno"+id).remove();
        },
        complete: function(response)
        {

        }
    })
}


function deleteNote(id)
{

}

function newnotepopup()
{/*
    var titlelabel = document.createElement("label");
    titlelabel.innerHTML="title: ";
    var titleField = document.createElement("input");
    titleField.setAttribute("type","text");
    titlelabel.appendChild(titleField);
    var createButton = document.createElement("input");
    createButton.setAttribute("type","submit");
    createButton.setAttribute("onclick","createNote()");
    var form = document.getElementById("newnoteform");
    form.appendChild(titlelabel);
    form.appendChild(createButton);
    */
    document.getElementById("newnotediv").style.visibility="visible";
}


function saveNotes()
{
    console.log($("#textid").val());
    saved = true;
    $("#savedID").css('color','green');
    console.log("saving notes");
    $.ajax({
        url:"index.php?action=savenote",
        type:'POST',
        data : {textData : $('#textid').val(), titleData : $("#titleid").val(), noteid:$("#noteID").val(), colour:$("#colourid").val()},
        success: function()
        {
                setTimeout(saveNotes, 1000)
        },
        complete: function(response)
        {

        }
    })
}



function addLink()
{
    alert($("#newlinkdiv").style.visibility="visible");
}



function sendNoteAsMail()
{
    $.ajax({
        type: "POST",
    url: "https://mandrillapp.com/api/1.0/messages/send.json",
    data: {
    "key": "jzYtZum9FGfYsYEQQZC6qg",
    'message': {
        'from_email': 'clippy@it-ca.net',
        'to': [
                {
            'email': 'meeusdylan@hotmail.com',
        'name': 'Dylan',
        'type': 'to'
        }
    ],
    'autotext': 'true',
    'subject': 'The note left on it-ca',
    'html': 'You have a note open on it-ca!!'
    }
}
}).done(function(response) {
    console.log(response); // if you're into that sorta thing
});
}






// FAVICON STUFF (which doesn't work unfortunately)

jQuery.fn.favicons = function (conf) {
    var config = jQuery.extend({
        insert:        'appendTo',
        defaultIco: 'favicon.png'
    }, conf);

    return this.each(function () {
        jQuery('a[href^="http://"]', this).each(function () {
            var link        = jQuery(this);
            var faviconURL    = link.attr('href').replace(/^(http:\/\/[^\/]+).*$/, '$1') + '/favicon.ico';
            var faviconIMG    = jQuery('<img src="' + config.defaultIco + '" alt="" />')[config.insert](link);
            var extImg        = new Image();

            extImg.src = faviconURL;

            if (extImg.complete) {
                alert("complete");
                faviconIMG.attr('src', faviconURL);
            }
            else {
                extImg.onload = function () {
                    faviconIMG.attr('src', faviconURL);
                };
            }
        });
    });
};

function getIcons()
{
    console.log("Getting icons <3");
    jQuery('#oldlinks').favicons({insert: 'insertBefore'});

}

function noteLookup(){
    var note = document.getElementById("lookup").value;
        $.ajax({
            type: "GET",
            url: "index.php?action=notelookup&word=" + note,
            error: function (xhr, ajaxOptions, thrownError) {
                // don't push errors to the user like this.
            },
            success: function (result) {
                setTimeout(function(){noteLookup();}, 3000);
            }
        });
}

window.onload=getIcons();

$(window).bind('keydown',function(event){
    if (event.ctrlKey || event.metaKey) {
        switch (String.fromCharCode(event.which).toLowerCase()) {
            case 's':
                event.preventDefault();
                saveNotes();
                break;
        }
    }
});