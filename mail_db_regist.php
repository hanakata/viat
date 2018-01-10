<?php
include 'include/db_connect.php';

if (isset($_POST['microsoft_item']) && is_array($_POST['microsoft_item'])) {
    $microsoft_item = $_POST['microsoft_item'];
}else{
    $microsoft_item = "";
}
if (isset($_POST['cybozu_item']) && is_array($_POST['cybozu_item'])) {
    $cybozu_item = $_POST['cybozu_item'];
}else{
    $cybozu_item = "";
}
if (isset($_POST['linux_item']) && is_array($_POST['linux_item'])) {
    $linux_item = $_POST['linux_item'];
}else{
    $linux_item = "";
}
if (isset($_POST['qnap_item']) && is_array($_POST['qnap_item'])) {
    $qnap_item = $_POST['qnap_item'];
}else{
    $qnap_item = "";
}

$company_code = $_POST['company_code'];

$query = "delete from user_sent_setting where company_code = '".$company_code."'";
$sql_query = mysqli_query($link,$query);

if ($microsoft_item != ""){
    foreach($microsoft_item as $value){
        $query = "INSERT INTO user_sent_setting VALUES ('".$company_code."','microsoft','".$value."')";
        $microsoft_query = mysqli_query($link,$query);
    }
}

if ($cybozu_item != ""){
    foreach($cybozu_item as $value){
        $query = "INSERT INTO user_sent_setting VALUES ('".$company_code."','cybozu','".$value."')";
        $cybozu_query = mysqli_query($link,$query);
    }
}

if ($linux_item != ""){
    foreach($linux_item as $value){
        $query = "INSERT INTO user_sent_setting VALUES ('".$company_code."','linux','".$value."')";
        $linux_query = mysqli_query($link,$query);
    }
}

if ($qnap_item != ""){
    foreach($qnap_item as $value){
        $query = "INSERT INTO user_sent_setting VALUES ('".$company_code."','qnap','".$value."')";
        $qnap_query = mysqli_query($link,$query);
    }
}

$url = './company_setting.php';
header('Location: ' . $url, true , 301);

?>