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
                <a class="navbar-brand" href=<?php echo  "index.php?action=gotonotelist&sharednoteid=" . $this->note->getID()?>>++Notepad</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">

                <ul class="nav navbar-nav">
                    <li class=""><a href=<?php echo  "index.php?action=gotonotelist&sharednoteid=" . $this->note->getID()?>>My Notes</a></li>
                    <li><a href=<?php echo  "index.php?action=gotoSharedNotes&sharednoteid=" . $this->note->getID()?>>Shared Notes</a></li>
                    <li><a href=<?php echo  "index.php?action=gotoaccount&sharednoteid=" . $this->note->getID()?>>Account</a></li>
                    <li class=""><a href=<?php echo  "index.php?action=logout&sharednoteid=" . $this->note->getID()?>>Logout</a></li>
                </ul>
            </div>
        </div>

            <!--/.nav-collapse -->
    </nav>




    <div class="starter-template">
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

    <?php if($this->right == 1 || $this->right == 3 || $this->right == null)
    { ?>

    <h3 id="savedID">Saved</h3>

    <div id="notetextdiv"  class="form-group">
        <input style="font-size:25px" type="text" id="titleid" <?php echo "value=\"" . $this->note->getTitle() ."\""?>/>  <label>Colour:<input id="colourid" class="color"></label>
        <label> cipher: <input type="checkbox" name="cipherbox" id="cipherbox" value="cipher"/></label>
        <?php if($this->shared){ ?>
            <label>Shared users:</label>
            <?php foreach($this->note->getSharedUsers() as $user){ echo $user->getUsername();
                 if ($user->getID() != $this->note->getUserID() && $_SESSION["user"]->getID() == $this->note->getUserID()){ ?>
        <a class="deleteuser" href=<?php echo "index.php?action=deleteuser&userid=" . $user->getID() . "&noteid=" . $this->note->getID() ?>> x</a>
                 <?php }  ?> &nbsp;
            <?php } } ?>
        </br>
        <label for="message"></label>
        <textarea class="form-control" rows="10" id="textid" ><?php echo $this->note->getText() ?></textarea>
    </div>
    <button class="btn btn-default btn-primary" onclick="saveNotes()" value="button">Save</button>
    <button class="btn btn-default btn-primary" onclick="addLink()"> Add link</button>

    <br/><br/>

    <div id="newlinkdiv">
        <label>Link: </label><input type="text" id="linkurl"/>
        <label>Name: </label><input type="text" id="linkname"/>
        <button class="btn btn-default btn-primary" onclick="saveLink()">Save</button>
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
        <?php if(!$this->shared){ ?>
        <div id="makeshared">
            <form id="makeshared" method="POST" action="index.php?action=makeshared">
                <input type="hidden" id="noteID" name="noteID" <?php echo "value=\"" . $this->note->getID() . "\""?>/>
                <input type="submit" class="btn btn-default btn-primary" value="Make Shared"/>
            </form>
        </div>
    <?php } ?>
    <br/>
    <?php if($this->shared && $_SESSION["user"]->getID() == $this->note->getUserID()){ ?>
    <div id="addusers">
            <form id="userform" method="POST" action="index.php?action=addsharedusers">
            <input type="hidden" id="noteID" name="noteID" <?php echo "value=\"" . $this->note->getID() . "\""?>/>
        <div id="users">
        </div>
        <br/>
        <button type="button" class="btn btn-default btn-primary" onclick="addUserInNotepage()">Add other user</button>
        <input type="submit" id="adduserbutton" class="btn btn-default btn-primary" value="Confirm"/>
     </form>
        <?php
    } ?>
        </div>

    <?php  } else{ ?>
     <br/><br/><br/>
    <h1><?php echo $this->note->getTitle(); ?></h1>
    <label>Shared users:</label>
    <?php foreach($this->note->getSharedUsers() as $user){ echo $user->getUsername();  ?> &nbsp; <?php } ?>
    <br/><br/>
    <p><?php echo $this->note->getText(); ?></p>
    <?php } ?>


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
