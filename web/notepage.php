<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Notepad</title>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.js"></script>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/singlenotepage.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-wartning.js"></script>
    <script type="text/javascript" src="js/notes.js"></script>
    <script type="text/javascript" src="js/favicons.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- colour picker test -->
    <script type="text/javascript" src="jscolor/jscolor.js"></script>
</head>

<body onload="setupNoteDetailPage()">


<input type="hidden" id="noteID" <?php echo "value=\"" . $this->note->getID() . "\""?>/>
<input type="hidden" id="noteTitle" <?php echo "value=\"" . $this->note->getTitle() ."\""?>/>
<input type="hidden" id="noteText" <?php echo "value=\"" . $this->note->getText()  . "\""?>/>
<input type="hidden" id="originalColour" value="<?php echo $this->note->getColour()?>"/>






<div class="container" id="containerdiv">

    <!-- BOOTSTRAPPED NAVIGATION!!! -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php?action=gotonotelist">++Notepad</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class=""><a href="index.php?action=gotonotelist">My Notes</a></li>
                    <li><a href="index.php?action=gotoSharedNotes">Shared Notes</a></li>
                    <li><a href="index.php?action=gotoaccount">Account</a></li>
                    <li class=""><a href="index.php?action=logout">Logout</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>




    <div class="starter-template">
    </div>

    <div id="navigation">
   <!--     <a class="notelink" href="index.php?action=gotonotelist">My Notes</a> -->
    </div>

<!--    <h1>notepad!</h1> -->

        </br>
    </br>
    </br>

    <!-- loop over the notifications -->
    <?php if($this->notifications != null){
        foreach($this->notifications as $notif) {?>
            <p class="lead" style="color:green"><?php echo $notif ?></p>
        <?php }}?>

    <!-- let's not forget about the errors! -->

    <?php if($this->errors != null){
        foreach($this->errors as $error){ ?>
            <p class="lead" style="color:red"><?php echo $error?></p>

        <?php }} ?>

    <h3 id="savedID">Saved</h3>

    <div id="notetextdiv"  class="form-group">
        <input style="font-size:25px" type="text" id="titleid" <?php echo "value=\"" . $this->note->getTitle() ."\""?>/>  <label>Colour:<input id="colourid" class="color"></label>  <label>Shared users:</label> <?php foreach($this->note->getSharedUsers() as $user){ echo $user->getUsername(); ?> &nbsp; <?php } ?>
        </br>
        <label for="message"></label>
        <textarea class="form-control" rows="10" id="textid"><?php echo $this->note->getText() ?></textarea>
    </div>
    <button onclick="saveNotes()" value="button">Save</button>
    <br/>
    <button onclick="addLink()"> Add link</button>

    </br>

    <div id="newlinkdiv">
        <label>link: <input type="text" id="linkurl"/></label>
        <label>name: <input type="text" id="linkname"/></label>
        <button onclick="saveLink()">Save</button>
    </div>
<!--
    <button onclick="sendNoteAsMail()">Mail</button>
-->


    <div id="oldlinks">
        <?php   if($this->notelinks != null && sizeof($this->notelinks > 0)){?>   <h2>Pinned Links</h2> <?php  foreach ($this->notelinks as $link) {
            ?>
            <a target="_blank" id="linkno<?php echo $link->getID()?>"  class="linkstyle" href="<?php echo $link->getUrl()?>"> <?php echo $link->getName() ?></a>
            <button id="buttonno<?php echo $link->getID()?>" onclick="deleteLink(<?php echo $link->getID()?>)"    style="width:20px; height:20px"> x</button>
            </br>
            <?php
        }} ?>
</div>
    <br/>
    <?php if($this->shared){ ?>
    <div id="users">

    </div>
        <br/>
        <input type="button" class="btn btn-default btn-primary" value="Add other user" onclick="addUser()"/>
        <input type="button" class="btn btn-default btn-primary" value="Confirm"/>
        <?php
    } ?>
        <!--
        THIS FAVICON STUFF DOES NOT WORK.
        <ul>
    <li><a href="http://www.danwebb.net">google</a></li>
        </ul>
    </div>
    <button onclick="getIcons()">ICON</button>
    -->

</div>
<!-- /.container -->



<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="bootstrap/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
