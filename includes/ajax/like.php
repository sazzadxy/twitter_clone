<?php

include  $_SERVER['DOCUMENT_ROOT'] .'/twitter_clone/core/init.php';
#include './core/init.php';
use TwitterClone\User\User;

User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_POST['like']) && !empty($_POST['like'])) {
    $user_id = $_SESSION['user_id'];
    $tweet_id = $_POST['like'];
    $get_id = $_POST['user_id'];
    $getFromT->addLike($user_id, $tweet_id, $get_id);
    $getFromU->create('likes', array('likeBy' => $user_id, 'likeOn' => $tweet_id));    
}

if (isset($_POST['unlike']) && !empty($_POST['unlike'])) {
    $user_id = $_SESSION['user_id'];
    $tweet_id = $_POST['unlike'];
    $get_id = $_POST['user_id'];
    $getFromT->unlike($user_id, $tweet_id, $get_id);
}

?>