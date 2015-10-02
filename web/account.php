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

    <title>Account</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/account.js"></script>
    <!-- Custom styles for this template -->
    <link href="css/singleform.css" rel="stylesheet">
    <link href="css/customcss.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>


<div class="container">


    <!-- BOOTSTRAPPED NAVIGATION!!! -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span
                        class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">++Notepad</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class=""><a href="index.php?action=gotonotelist">My Notes</a></li>
                    <li><a href="index.php?action=gotoSharedNotes">Shared Notes</a></li>
                    <li class="active"><a href="#">Account</a></li>
                    <li class=""><a href="index.php?action=logout">Logout</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>

    </br>

    <!-- loop over the notifications -->
    <?php if ($this->notifications != null)
    {
        foreach ($this->notifications as $notif)
        { ?>
            <p class="lead" style="color:green"><?php echo $notif ?></p>
        <?php }
    } ?>

    <!-- let's not forget about the errors! -->

    <?php if ($this->errors != null)
    {
        foreach ($this->errors as $error)
        { ?>
            <p class="lead" style="color:red"><?php echo $error?></p>

        <?php }
    } ?>


    <h1>Account Hub</h1>

    <input type="button" class="btn btn-lg btn-primary btn-block" value="Change Password"
           onclick="location.href='index.php?action=gotochangepassword'">
    </br>

    <?php
    $user = $_SESSION["user"];
    if ($user->getAPIKey() == "0xDEAD")
    {
    ?>
        <input type = "button" class="btn btn-lg btn-primary btn-block" value = "get API key" onclick = "generateKey()" >
    <?php } else
    {
  ?>
        <p class="lead" style="color:slateblue">API key: <?php echo $user->getAPIKey()?></p>
    <?php
    } ?>
    <h1>API keys</h1>

    <p class="lead">What's the deal with the API key you ask? I'll explain it to you...</p>

    <p class="lead">There is a <a href="http://www.it-ca.net/notepad/downloads/itcanotepadplugin.rar">chrome plugin</a> associated with this cloud notepad application.
        To use this plugin, we will ask you for your API key, this provides us with a way to authenticate your account.
        This way, we can assure you that no one else can access your account data.</p>

    <p class="lead">Installation is a bit messy - mainly because it's early access. Like almost all survival games on steam, and in good tradition that means this has some .. nuissances.
    The main issue is that you'll have to install this as a developer under chrome. Chrome will give you a warning about this, but you can ignore this. The code for this project is open-source anyway,
        so if you're a curious cat, you can go check that out on <a href="http://www.github.com/DylanMeeus">github</a>!
    </p>


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
