
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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript" src="js/account.js"></script>
</head>

<body>


<div class="container">

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

    <p>
        Don't worry, we can all forget sometimes. Enter your email address to recover the password associate with your account.
    </p>

    <fieldset class="form-signin" method="POST" action="index.php?action=startpasswordrecovery">
        <label for="inputMail" class="sr-only">Email*</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Mail" required autofocus>
        <button onclick="recoverPassword()">Recover</button>
    </fieldset>
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
