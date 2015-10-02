
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

    <!-- Custom styles for this template -->
    <link href="css/singleform.css" rel="stylesheet">
    <link href="css/customcss.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
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
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
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

    <h1>Change password</h1>

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

    <h2 class="form-signin-heading">Change your password!</h2>
    <form method="post" action="index.php?action=changePassword">
        <label for="inputPassword" class="sr-only">Old password</label> <input type="password" name="oldpassword" id="oldpassword" class="form-control"
                                                                               placeholder="Old Password" required autofocus>
        <label for="inputPassword" class="sr-only">New password</label> <input type="password" name="newpassword" id="inputPassword" class="form-control" placeholder="Password" required>
        <label for="repeatPassword" class="sr-only">Repeat password</label> <input type="password" name="repeatpassword" id="repeatPassword" class="form-control" placeholder="Repeat password" required>


        <input type="submit" class="btn btn-lg btn-primary btn-block" id="changebutton" value="change!">
    </form>
    <h1>About passwords...</h1>
    <p class="lead"> Okay so this is kind of important. You will want to use a GOOD password, but <b>NEVER</b> reuse a GOOD password! The password
        you use for this website should be <b>UNIQUE.</b> </p>
    <p class="lead">How do I create a good password you ask? Well, just follow this image. It's made by Randall Munroe for his website <a href="http://www.xkcd.com">XKCD</a>. </p>
    <img src="https://imgs.xkcd.com/comics/password_strength.png">
    <p>Please, I beg of you, do NOT use 'correct horse battery staple', as that is most likely included in a dictionary attack after this comic was released...</p>


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
