<?php

use TwitterClone\User\User;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';

User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_GET['showNotification']) && !empty($_GET['showNotification'])) {
    $user_id = $_SESSION['user_id'];
    $data    = $getFromM->getNotificationCount($user_id);
    echo json_encode(array('notification' => $data->totalN, 'messages' => $data->totalM));

} else {
    User::redirect('index.php');
}
?>