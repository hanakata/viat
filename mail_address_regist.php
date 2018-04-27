<?php
include 'include/db_connect.php';

$mail_address_to = $_POST['mail_address_to'];
$mail_address_cc = $_POST['mail_address_cc'];
$mail_address_bcc = $_POST['mail_address_bcc'];
$company_code = $_POST['company_code'];
$query = "delete from user_mail_setting where company_code = '".$company_code."'";
$sql_query = mysqli_query($link,$query);

$query = "INSERT INTO user_mail_setting VALUES ('".$company_code."','".$mail_address_to."','".$mail_address_cc."','".$mail_address_bcc."')";
$mail_query = mysqli_query($link,$query);

echo $query;

$url = './mail_setting.php';
header('Location: ' . $url, true , 301);

?>