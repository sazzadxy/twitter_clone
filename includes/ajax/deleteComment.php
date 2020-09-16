<?php
include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
use TwitterClone\User\User;
#include './core/init.php';
User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_POST['deleteComment']) && !empty($_POST['deleteComment'])) {
    $user_id    = $_SESSION['user_id'];
    $comment_id = $_POST['deleteComment'];
    $getFromU->delete('comments', array('commentID' => $comment_id, 'commentBy' => $user_id));
}


?>