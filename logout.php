<?php
session_start();
$_SESSION['sess_user_id'] = "";
$_SESSION['sess_name'] = "";
if (empty($_SESSION['sess_user_id'])) header("location: page-login.php");
?>