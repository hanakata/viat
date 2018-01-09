<?php
include 'include/db_connect.php';

$mail_address = $_POST['mail_address'];
$company_code = $_POST['company_code'];
$query = "UPDATE user_setting SET user_mail = '".$mail_address."' WHERE company_code = '".$company_code."'";
$mail_query = mysqli_query($link,$query);

$url = './mail_setting.php';
header('Location: ' . $url, true , 301);

?>