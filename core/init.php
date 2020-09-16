<?php
include $_SERVER['DOCUMENT_ROOT'].'/twitter_clone/vendor/autoload.php';

use TwitterClone\Database\Database;
use TwitterClone\Follow\Follow;
use TwitterClone\Tweet\Tweet;
use TwitterClone\User\User;
use TwitterClone\Message\Message;

global $db;
$getFromD = new Database;
$db = $getFromD->getDB();
$getFromU = new User($db);
$getFromT = new Tweet($db);
$getFromF = new Follow($db);
$getFromM = new Message($db);


session_start();

?>