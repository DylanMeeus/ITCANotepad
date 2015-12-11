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

    <title>Register</title>


    <!-- first of all, load jquery -->
    <script src="js/jquery.js"></script>
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
    <!-- make sure we alwyas load this javascript file for the validation functions. -->
    <script src="js/login.js"></script>
</head>

<body>


<div class="container">

    <form class="form-signin" method="POST" action="index.php?action=register">
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

        <h2 class="form-signin-heading">Register</h2>
            <!-- javascript query for username availability? -->
            <!-- hide the button as long as the username is not accepted -->
        <label for="inputUsername" class="sr-only">Username</label>
        <input oninput="checkUsername()" onchange="checkUsername()" type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <label for="repeatPassword" class="sr-only">Repeat password</label>
        <!-- javascript can check this on the fly -->
        <!-- colour it red if !match -->
        <input type="password" name="repeatPassword" id="repeatPassword" class="form-control" placeholder="Repeat password" required/>
  <!--      <label for="inputToken" class="sr-only">Token</label>
        <input type="text" name="token" id="inputToken" class="form-control" placeholder="Token" required>-->
        <label for="inputMail" class="sr-only">Email*</label>
        <input type="text" name="mail" id="mail" class="form-control" placeholder="Mail" autofocus>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Create">
    </form>


    <p>* the e-mail field is not required. We respect your privacy and agree that using your e-mail address all around the web can be a bad idea. We
    do however offer the possibility so you can restore your password in case you lost it. You e-mail will not be used for any other purposes, and you can set it up
    at any time.</p>
</div>
<!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../bootstrap/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
