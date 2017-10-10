<?php

include_once "Credentials.php";

session_start();
if(isset($_SESSION['authorized']))
{
    setcookie(session_name(),session_id(),time()-1000, "/");
    unset($_SESSION);
    session_destroy();
}
die(header("location:index.php"));