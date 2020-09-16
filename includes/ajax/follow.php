<?php
include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';
use TwitterClone\User\User;

User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_POST['unfollow']) && !empty($_POST['unfollow'])) {
    $user_id   = $_SESSION['user_id'];
    $follow_id = $_POST['unfollow'];
    $profile_id = $_POST['profile'];
    $getFromF->unfollow($follow_id, $user_id, $profile_id);
}

if (isset($_POST['follow']) && !empty($_POST['follow'])) {
    $user_id   = $_SESSION['user_id'];
    $follow_id = $_POST['follow'];
    $profile_id = $_POST['profile'];
    $getFromF->follow($follow_id, $user_id, $profile_id);
}



?>