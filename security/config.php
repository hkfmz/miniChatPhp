<?php
error_reporting(E_ALL);
include_once(__DIR__.'/setting.php');
    
try {
    $pdo = new PDO('mysql:host='. DB_HOST .';dbname='.DB_DATABASE, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    $pdo='Error: '.$e->getMessage();
    die($pdo);
}
    return $pdo;
    
//By Hegel Motokoua
