<?php

include_once "Credentials.php";

session_start();

if(isset($_SESSION['authorized']))
{
    die(header("location:create.php"));
}

if (isset($_POST['login']) && isset($_POST['password'])) {

    if(Credentials::getLogin() === $_POST['login'] && password_verify($_POST['password'], Credentials::getPassword())){
        $_SESSION['authorized'] = true;
        unset($_SESSION['login']);
        unset($_SESSION['password']);
        die(header("location:create.php"));
    }
    else{
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['password'] = $_POST['password'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Sign in</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/blog-home.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">My BLOG</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                if(isset($_SESSION['authorized'])){
                    echo "<li class='active'><a href=\"create.php\">Create</a></li>";
                    echo "<li><a href=\"signOut.php\">Sign out</a></li>";
                }
                else{
                    echo "<li><a href=\"signIn.php\">Sign in</a></li>";
                }
                ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-lg-8">
            <form action="signIn.php" method="post" class="form-horizontal" role="form">
                <div class="form-group">
                    <?php
                    if(!isset($_SESSION['authorized']) && isset($_SESSION['login'])){
                        echo "<legend class='text-danger'>INCORRECT LOGIN OR PASSWORD</legend>";
                    }
                    ?>
                    <legend>Fill in the form:</legend>
                </div>
                <div class="form-group">
                    <label for="login" class="col-sm-2 control-label">Login:</label>
                    <div class="col-sm-10">
                        <input type="text" name="login" id="login" class="form-control" value=<?php echo isset($_SESSION['login']) ? "'" . $_SESSION['login'] . "'" : "''" ?> title="" required="required">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Password:</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" id="password" class="form-control" value=<?php echo isset($_SESSION['password']) ? "'" . $_SESSION['password'] . "'" : "''" ?> title="" required="required">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <!-- /.row -->

    <hr>

    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; Your Website 2014</p>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </footer>

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>

</html>
