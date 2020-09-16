<?php 
include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
use TwitterClone\User\User;
#include './core/init.php';
User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_POST['comment']) && !empty($_POST['comment'])) {
    $comment    = $getFromU->checkInput($_POST['comment']);
    $user_id    = $_SESSION['user_id'];
    $tweet_id   = $_POST['tweet_id'];

    if (!empty($comment)) {
        $getFromU->create('comments', array('comment' => $comment, 'commentOn'=> $tweet_id, 'commentBy' => $user_id, 'commentAt' => date('Y-m-d H:i:s')));
        $comments = $getFromT->comments($tweet_id);
        $tweet    = $getFromT->getPopupTweet($tweet_id);

        foreach ($comments as $comment) {
            echo '<div class="tweet-show-popup-comment-box">
            <div class="tweet-show-popup-comment-inner">
                <div class="tweet-show-popup-comment-head">
                    <div class="tweet-show-popup-comment-head-left">
                         <div class="tweet-show-popup-comment-img">
                             <img src="'.BASE_URL.$comment->profileImage.'">
                         </div>
                    </div>
                    <div class="tweet-show-popup-comment-head-right">
                          <div class="tweet-show-popup-comment-name-box">
                             <div class="tweet-show-popup-comment-name-box-name"> 
                                 <a href="'.BASE_URL.$comment->username.'">'.$comment->screenName.'</a>
                             </div>
                             <div class="tweet-show-popup-comment-name-box-tname">
                                 <a href="'.BASE_URL.$comment->username.'">@'.$comment->username.' - '.$comment->commentAt.'</a>
                             </div>
                         </div>
                         <div class="tweet-show-popup-comment-right-tweet">
                                 <p><a href="'.BASE_URL.$tweet->username.'">@'.$tweet->username.'</a> '.$comment->comment.'</p>
                         </div>
                         <div class="tweet-show-popup-footer-menu">
                            <ul>
                                <li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>
                                <li><a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                                '.(($comment->commentBy === $user_id) ? '
                                <li>
                                <a href="#" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                                <ul> 
                                  <li><label class="deleteComment" data-tweet="' . $tweet->tweetID . '" data-comment="' . $comment->commentID . '">Delete Tweet</label></li>
                                </ul>
                                </li>' : '').'
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--TWEET SHOW POPUP COMMENT inner END-->
            </div>
            ';
        }
    }
}

?>