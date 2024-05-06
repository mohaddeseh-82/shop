<?php
$db = new PDO('mysql:host=localhost;dbname=shop;charset=utf8','root','');

function db(){
    global $db;
    return $db;
}
?>