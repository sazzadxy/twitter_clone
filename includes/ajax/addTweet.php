<?php
include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';

#include './core/init.php';
use TwitterClone\User\User;
use TwitterClone\File\File;

User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_POST) && !empty($_POST)) {
    $status     = $getFromU->checkInput($_POST['status']);
    $user_id    = $_SESSION['user_id'];
    $tweetImage = '';

    if (!empty($status) or !empty($_FILES['file']['name'][0])) {
		if (!empty($_FILES['file']['name'][0])) {
			$tweetImage = File::uploadImage($_FILES['file']);
		} 
		
		if (strlen($status > 140)) {
			$error = "Your tweet is too long!";
		}
	
			$tweet_id = $getFromU->create('tweets', array('status'=> $status, 'tweetBy' => $user_id, 'tweetImage' => $tweetImage, 'retweetID' => 0, 'retweetBy' => 0, 'likesCount' => 0, 'retweetCount' => 0, 'retweetMsg' => '', 'postedOn' => date('Y-m-d H:i:s')));
			preg_match_all("/#+([a-zA-Z0-9_]+)/i", $status, $hashtag);
			
			if (!empty($hashtag)) {
				$getFromT->addTrend($status);
			}
			$getFromT->addMention($status, $user_id, $tweet_id);
			
			$success = "Yout tweet has been posted";
			$result['success'] = $success;
			echo json_encode($result);
			
		    User::redirect('home.php');
	} else {
		$error = "Type or choose image to tweet";
	}

	if (isset($error)) {
		$result['error'] = $error;   
		echo json_encode($result);
	}
}

?>