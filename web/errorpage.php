
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

    <title>Error!</title>

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

<body onload="sleep()">


<div class="container">
    <h1>Oh oh!</h1>
    <p class="lead">It seems like you tried to do something unholy... </p>
    <p class="lead">Not to worry though, you will be redirected back to a place you <b>belong</b> in a few seconds..</p>
    <p id="counter" class="lead"></p>

</div>
<!-- /container -->


<!-- Placed at the end of the document so the pages load faster -->
<!-- Bootstrap core JavaScript
================================================== -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../bootstrap/js/ie10-viewport-bug-workaround.js"></script>
<script type="text/javascript">


    var seconds = 10;
    function sleep()
    {
        var test = document.getElementById("counter");
        setInterval(redirect,1000);
    }

    function redirect()
    {
        counter.innerHTML = seconds + "...";
        seconds -= 1;
        if(seconds==0)
        window.location="index.php?action=gotologin";
    }
</script>
</body>
</html>
