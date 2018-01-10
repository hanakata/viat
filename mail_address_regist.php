<?php
include 'include/db_connect.php';

$mail_address = $_POST['mail_address'];
$company_code = $_POST['company_code'];
$query = "delete from user_mail_setting where company_code = '".$company_code."'";
$sql_query = mysqli_query($link,$query);

$query = "INSERT INTO user_mail_setting VALUES ('".$company_code."','".$mail_address."')";
$mail_query = mysqli_query($link,$query);

$url = './mail_setting.php';
header('Location: ' . $url, true , 301);

?>