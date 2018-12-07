<?php
$link = mysqli_connect('localhost', 'root', '<password>','kb_checker');
if (! $link) {
    die('not connect ' . mysqli_error());
}
?>