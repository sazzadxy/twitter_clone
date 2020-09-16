<?php

use TwitterClone\File\File;
use TwitterClone\User\User;
include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';
User::preventAccess($_SERVER['REQUEST_METHOD'], realpath(__FILE__), realpath($_SERVER['SCRIPT_FILENAME']));

if (isset($_POST['deleteTweet']) && !empty($_POST['deleteTweet'])) {
    $tweet_id = $_POST['deleteTweet']; 
    $user_id  = $_SESSION['user_id'];
    $getFromU->delete('tweets', array('tweetID' => $tweet_id, 'tweetBy' => $user_id));
}

if (isset($_POST['showPopup']) && !empty($_POST['showPopup'])) {
    $tweet_id = $_POST['showPopup'];
    $user_id  = $_SESSION['user_id'];
    $tweet    = $getFromT->getPopupTweet($tweet_id);
?>
    <div class="retweet-popup">
        <div class="wrap5">
            <div class="retweet-popup-body-wrap">
                <div class="retweet-popup-heading">
                    <h3>Are you sure you want to delete this Tweet?</h3>
                    <span><button class="close-retweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
                </div>
                <div class="retweet-popup-inner-body">
                    <div class="retweet-popup-inner-body-inner">
                        <div class="retweet-popup-comment-wrap">
                            <div class="retweet-popup-comment-head">
                                <img src="<?php echo BASE_URL.$tweet->profileImage; ?>" />
                            </div>
                            <div class="retweet-popup-comment-right-wrap">
                                <div class="retweet-popup-comment-headline">
                                    <a><?php echo $tweet->screenName; ?> </a><span>‏@<?php echo $tweet->username.' '.File::timeAgo($tweet->postedOn); ?></span>
                                </div>
                                <div class="retweet-popup-comment-body">
                                <!-- <?php echo $tweet->status.' '.$tweet->tweetImage; ?> -->
                                <?php echo $tweet->status; ?>
                                <?php (!empty($tweet->tweetImage)) ? "<img src='<?php echo BASE_URL.$tweet->tweetImage; ?>'/>" : " " ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="retweet-popup-footer">
                    <div class="retweet-popup-footer-right">
                        <button class="cancel-it f-btn">Cancel</button><button class="delete-it" type="submit">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }



?>