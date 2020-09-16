<?php
include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';
use TwitterClone\User\User;

User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_POST['fetchPosts']) && !empty($_POST['fetchPosts'])) {
    $user_id = $_SESSION['user_id'];
    $limit   = (int) trim($_POST['fetchPosts']);
    $getFromT->tweets($user_id, $limit);
}

?>