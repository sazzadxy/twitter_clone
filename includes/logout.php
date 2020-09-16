<?php

#include '../vendor/autoload.php';
#include '../core/init.php';
include $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
use TwitterClone\User\User;

if ($getFromU->isLoggedIn() === false) {
	User::redirect('index.php');
}

User::logout();

?>