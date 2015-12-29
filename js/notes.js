/**
 * Created by Dylan on 1/08/2015.
 */



var saved = true;
var extraButtons = false;
var sharedUsers = 1;
var datalist = false;
var usernames;
function setupNoteDetailPage()
{
    document.getElementById("adduserbutton").style.display = 'none';
    var x = document.getElementById("newlinkdiv");
 //   x.style.visibility="hidden";
    $("#savedID").css('color','green');
    $("#textid").keyup( function (){
        saved = false;
        $("#savedID").css('color','red');
    });

    $("#colourid").val($("#originalColour").val());
    // before we bounce - we check the stuff
        if ($("#textid").val() == "") {
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
    hideNewNote();

    $("#notelist").hide().fadeIn(1500);
    // apply fade-in to the other div?
    noteLookup();
}

function hideNewNote(){
    hide(document.getElementById("newnotediv"));
}

function cancelUsers(){
    var node = document.getElementById("users");
    while(node.hasChildNodes()) {
        node.removeChild(node.lastChild);
    }
    sharedUsers = 1;
    document.getElementById("cancelbutton").style.display = 'none';
    document.getElementById("removeuser").style.display = 'none';
    document.getElementById("adduserbutton").style.display = 'none';
}

function hide (elements) {
    elements = elements.length ? elements : [elements];
    for (var index = 0; index < elements.length; index++) {
        elements[index].style.display = 'none';
    }
}

function createNote(){
    show(document.getElementById("newnotediv"));
}

function show(elements){
    elements = elements.length ? elements : [elements];
    for (var index = 0; index < elements.length; index++) {
            elements[index].style.display = 'block';
    }

    $("#adduser").display = 'none';
}

function getUsernames(){
    $.ajax({
        url: "index.php?action=getUsers",
        type: "GET",
        datatype: "json",
        success: function (response) {

            var responseString = response;
            var escapedString = responseString.substring(1, responseString.length - 1).replace(/(['"])/g, "");
            usernames = escapedString.split(",");
        },
        complete: function (response) {
            // this just gets called when the ajax call is done. It's like the finally of a try-catch.
            console.log(response);
        }
    });
}

function removeuser(){
    //forbidden to delete first userfield (need to click cancel button to completely quit)
    if(sharedUsers > 2) {
        var node = document.getElementById("users");
        node.removeChild(node.lastChild);
        //we need to remove the <br> if it has just been added in the div
        if(sharedUsers % 3 == 1){
            node.removeChild(node.lastChild);
        }
        --sharedUsers;
    }
   // element.parentNode.removeChild(element);
    /*$("users").last().remove();
     $("users").last().remove();
     $("users").last().remove();
     $("users").last().remove();
     //if there's 3 shared user-fields, we need to delete the <br> element as well
     if (sharedUsers % 3 == 0) {
     $("users").last().remove();
     }*/
}

function addUserInNotepage(){
    addUser($("#userform"));
    $("#cancelbutton").click( function(){
        cancelUsers();
    });
    if(extraButtons){
        document.getElementById("cancelbutton").style.display = 'inline';
        document.getElementById("removeuser").style.display = 'inline';
        document.getElementById("adduserbutton").style.display = 'inline';
    }
}

function addUserAtOverview(){
    addUser($("#newnoteform"));
    $("#cancelbutton").click( function(){
        hideNewNote();
    });
}

function addUser(element){

    var userDiv = $("<div/>");
    userDiv.attr("id", "user" + sharedUsers);

    var label = $("<label/>");
    label.text("User " + sharedUsers + ":");


    var select = $("<select/>");
    select.attr("id", "rightID" + sharedUsers)
        .attr("name", "rightID" + sharedUsers);
    select.append($("<option></option>")
        .attr("value",2)
        .text("Read"));
    select.append($("<option></option>")
        .attr("value",3)
        .attr("selected", "selected")
        .text("Read/Write"));

    var txt = $("<input/>");
    txt.attr("type", "text");
    txt.attr("id", "username" + sharedUsers);
    txt.attr("name", "username" + sharedUsers);

    var usernameList = $("<datalist/>");
    usernameList.attr("id", "usernames" + sharedUsers);

    for (var i = 0; i < usernames.length; i++) {
        usernameList.append($("<option></option>")
            .attr("value", usernames[i])
            .text(usernames[i]));
    }

    txt.attr("list", "usernames" + sharedUsers);

    if(!extraButtons){
        $("#adduser").display = 'block';
        appendButtons(element);
        extraButtons = true;
    }


    userDiv.append(usernameList)
        .append(label)
        .append(txt)
        .append(select)
        .append("&nbsp;");

    $("#users").append(userDiv);
    document.getElementById("user" + sharedUsers).style.display = 'inline';
    if(sharedUsers % 3 == 0){
        var br = $("<br/>");
        $("#users").append(br);
    }
    sharedUsers++;
}

function appendButtons(element){
    var removeUser = $("<button/>");
    removeUser.attr("id", "removeuser");
    removeUser.attr("type", "button");
    removeUser.attr("class", "btn btn-default btn-primary");
    removeUser.text("Delete last user");
    var cancel = $("<button/>");
    cancel.attr("id", "cancelbutton");
    cancel.attr("class", "btn btn-default btn-primary");
    cancel.attr("type", "button");
    cancel.text("Cancel");
    /* var submit = $("<input/>");
     submit.attr("class", "btn btn-default btn-primary");
     submit.attr("value", "Create");*/
    var br = $("<br/>");
    element.append(removeUser)
        .append("&nbsp;");
    /* $("#newnoteform").append(submit)
     .append("&nbsp;");*/
    element.append(cancel);
    $("#removeuser").click( function(){
        removeuser();
    });
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