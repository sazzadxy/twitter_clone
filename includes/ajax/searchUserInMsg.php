<?php

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';

use TwitterClone\User\User;

User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_POST['search']) && !empty($_POST['search'])) {
    $user_id = $_SESSION['user_id'];
    $search  = $getFromU->checkInput($_POST['search']);
    $result  = $getFromU->search($search);

    echo '<h4>People</h4>
    <div class="message-recent"> ';
    foreach ($result as $user) {
        if ($user->user_id != $user_id) {
            echo '<div class="people-message" data-user="'.$user->user_id.'">
            <div class="people-inner">
                <div class="people-img">
                    <img src="'.BASE_URL.$user->profileImage.'"/>
                </div>
                <div class="name-right">
                    <span><a>'.$user->screenName.'</a></span><span>@'.$user->username.'</span>
                </div>
            </div>
         </div>';
        }
    }
    echo '</div>';
}
?>