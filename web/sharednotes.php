
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

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/singleform.css" rel="stylesheet">
    <link href="css/customcss.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>
    <script type="text/javascript" src="js/notes.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body onload="setupPage(), getUsernames()">


<div class="container">

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">++Notepad</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class=""><a href="index.php?action=gotonotelist">My Notes</a></li>
                    <li class="active"><a href="#">Shared Notes</a></li>
                    <li class=""><a href="index.php?action=gotoaccount">Account</a></li>
                    <li class=""><a href="index.php?action=logout">Logout</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>

    </br></br></br>

    <!--<?php
   /* for($i = 0; $i < sizeof($_GET['users']);$i++){ ?>
    <input type="hidden" id="username" . <?php echo $i; ?> value="<?php echo $_GET['users'][$i]->getUsername()?>"/>
    <?php }?>--> */?>

    <!-- loop over the notifications -->
    <?php if($this->notifications != null){
        foreach($this->notifications as $notif) {?>
            <p class="lead" style="color:green"><?php echo $notif ?></p>
        <?php }}?>

    <!-- let's not forget about the errors! -->


    <input type="button" class="btn btn-lg btn-primary btn-block" value="New shared note" onclick="newnotepopup()">
    <br/>
    <div class="clearfix">
        <?php if($this->errors != null){
            foreach($this->errors as $error){ ?>
                <p class="lead" style="color:red"><?php echo $error?></p>

            <?php }} ?>
       <label>Search: <input type="text" id="lookup" /></label>
    </div>
    <br/>
    <div id="newnotediv">
        <form id="newnoteform" method="POST" action="index.php?action=createsharednote">
            <label>Title: </label><input type="text" id="newnotetitle" name="newnotetitle"/>
            <br/><br/>
            <div id="users">
                <label>Share with:</label>
                <br/><br/>
            </select>
            </div>
            <br/>
            <input type="button" class="btn btn-default btn-primary" value="Add user" onclick="addUser()"/>
            <input type="submit" class="btn btn-default btn-primary" value="Create"/>
        </form>


    </div>


    <div id="notelist">
        <?php  if($this->notes != null){ foreach ($this->notes as $note) {
            ?>
           <p><a style="color:#<?php echo $note->getColour()?>" class="notelink" href=<?php echo "index.php?action=opensharednote&noteid=" . $note->getID()?>> <?php echo  $note->getTitle() ?></a> Owner: <?php echo $note->getSharedUsers()[0]->getUsername() ?>
            <?php if($_SESSION["user"]->getID() == $note->getSharedUsers()[0]->getID()){ ?><a class="deletenote" href=<?php echo "index.php?action=deletesharednote&noteid=" . $note->getID()?>> x</a></p><?php } ?>
            </br>
            <?php
        }} ?>
    </div>
</div>
<!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="bootstrap/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
