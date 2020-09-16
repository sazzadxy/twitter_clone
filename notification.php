<?php

use TwitterClone\File\File;
use TwitterClone\User\User;
use TwitterClone\Tweet\Tweet;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';

$user_id =  $_SESSION['user_id'];
$user    = $getFromU->userData($user_id);
$getFromM->notificationViewed($user_id);
$notify  = $getFromM->getNotificationCount($user_id);
$notification = $getFromM->notification($user_id);

// if (empty($user_id)) {
// 	User::redirect('index.php');
// }

if ($getFromU->isLoggedIn() === false) {
    User::redirect('index.php');
}


if (isset($_POST['tweet'])) {
    $status = $getFromU->checkInput($_POST['status']);
    $tweetImage = '';

    if (!empty($status) or !empty($_FILES['file']['name'][0])) {
        if (!empty($_FILES['file']['name'][0])) {
            $tweetImage = File::uploadImage($_FILES['file']);
        }

        if (strlen($status > 140)) {
            $error = "Your tweet is too long!";
        }

        $getFromU->create('tweets', array('status' => $status, 'tweetBy' => $user_id, 'tweetImage' => $tweetImage, 'retweetID' => 0, 'retweetBy' => 0, 'likesCount' => 0, 'retweetCount' => 0, 'retweetMsg' => '', 'postedOn' => date('Y-m-d H:i:s')));
        preg_match_all("/#+([a-zA-Z0-9_]+)/i", $status, $hashtag);

        if (!empty($hashtag)) {
            $getFromT->addTrend($status);
        }
        User::redirect('home.php');
    } else {
        $error = "Type or choose image to tweet";
    }
}



?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/header.php'; ?>
<?php File::ch_title('notifications '.$user->screenName.' (@' .$user->username.')'); ?>

<div class="wrapper">
    <!-- header wrapper -->
    <div class="header-wrapper">

        <div class="nav-container">
            <!-- Nav -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/nav.php' ?>
            <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
            <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/notification.js"></script>
            <!-- nav right ends-->
            <!-- nav container ends -->
        </div><!-- nav ends -->


    </div>

</div><!-- header wrapper end -->


<!---Inner wrapper-->
<div class="inner-wrapper">
    <div class="in-wrapper">
        <div class="in-full-wrap">
            <div class="in-left">
                <div class="in-left-wrap">
                    <div class="info-box">
                        <div class="info-inner">
                            <div class="info-in-head">
                                <!-- PROFILE-COVER-IMAGE -->
                                <img src="<?php echo BASE_URL . $user->profileCover; ?>" />
                            </div><!-- info in head end -->
                            <div class="info-in-body">
                                <div class="in-b-box">
                                    <div class="in-b-img">
                                        <!-- PROFILE-IMAGE -->
                                        <img src="<?php echo BASE_URL . $user->profileImage; ?>" />
                                    </div>
                                </div><!--  in b box end-->
                                <div class="info-body-name">
                                    <div class="in-b-name">
                                        <div><a href="<?php echo BASE_URL . $user->username; ?>"><?php echo $user->screenName; ?></a></div>
                                        <span><small><a href="<?php echo BASE_URL . $user->username; ?>">@<?php echo $user->username; ?></a></small></span>
                                    </div><!-- in b name end-->
                                </div><!-- info body name end-->
                            </div><!-- info in body end-->
                            <div class="info-in-footer">
                                <div class="number-wrapper">
                                    <div class="num-box">
                                        <div class="num-head">
                                            TWEETS
                                        </div>
                                        <div class="num-body">
                                            <?php echo $getFromT->countTweets($user_id); ?>
                                        </div>
                                    </div>
                                    <div class="num-box">
                                        <div class="num-head">
                                            FOLLOWING
                                        </div>
                                        <div class="num-body">
                                            <span class="count-following"><?php echo $user->following; ?></span>
                                        </div>
                                    </div>
                                    <div class="num-box">
                                        <div class="num-head">
                                            FOLLOWERS
                                        </div>
                                        <div class="num-body">
                                            <span class="count-followers"><?php echo $user->followers; ?></span>
                                        </div>
                                    </div>
                                </div><!-- mumber wrapper-->
                            </div><!-- info in footer -->
                        </div><!-- info inner end -->
                    </div><!-- info box end-->

                    <!--==TRENDS==-->
                    <?php $getFromT->trends(); ?>
                    <!--==TRENDS==-->

                </div><!-- in left wrap-->
            </div><!-- in left end-->
            <div class="in-center">
                <div class="in-center-wrap">
                    <!--NOTIFICATION WRAPPER FULL WRAPPER-->
                    <div class="notification-full-wrapper">

                        <div class="notification-full-head">
                            <div>
                                <a href="#">All</a>
                            </div>
                            <div>
                                <a href="#">Mention</a>
                            </div>
                            <div>
                                <a href="#">settings</a>
                            </div>
                        </div>
                        <?php foreach ($notification as $data) : ?>

                            <?php if($data->type == 'follow') : ?>
                        <!-- Follow Notification -->
                        <!--NOTIFICATION WRAPPER-->
                        <div class="notification-wrapper">
                            <div class="notification-inner">
                                <div class="notification-header">

                                    <div class="notification-img">
                                        <span class="follow-logo">
                                            <i class="fa fa-child" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="notification-name">
                                        <div>
                                            <img src="<?php echo BASE_URL.$data->profileImage; ?>" />
                                        </div>

                                    </div>
                                    <div class="notification-tweet">
                                        <a href="<?php echo BASE_URL.$data->username; ?>" class="notifi-name"><?php echo $data->screenName; ?></a><span> Followed you - <span><?php echo File::timeAgo($data->time); ?></span>

                                    </div>

                                </div>

                            </div>
                            <!--NOTIFICATION-INNER END-->
                        </div>
                        <!--NOTIFICATION WRAPPER END-->
                        <!-- Follow Notification -->
                            <?php endif; ?>

                    <?php if($data->type == 'like') : ?>
                        <!-- Like Notification -->
                        <!--NOTIFICATION WRAPPER-->
                        <div class="notification-wrapper">
                            <div class="notification-inner">
                                <div class="notification-header">
                                    <div class="notification-img">
                                        <span class="heart-logo">
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="notification-name">
                                        <div>
                                            <img src="<?php echo BASE_URL.$data->profileImage; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-tweet">
                                    <a href="<?php echo BASE_URL.$data->username; ?>" class="notifi-name"><?php echo $data->screenName; ?></a><span> liked your <?php if($data->tweetBy == $user_id){echo 'Tweet';}else{echo 'Retweet';} ?> - <span><?php echo File::timeAgo($data->time); ?></span>
                                </div>
                                <div class="notification-footer">
                                    <div class="noti-footer-inner">
                                        <div class="noti-footer-inner-left">
                                            <div class="t-h-c-name">
                                                <span><a href="<?php echo BASE_URL.$user->username; ?>"></a><?php echo $user->screenName; ?></span>
                                                <span>@<?php echo $user->username; ?></span>
                                                <span><?php echo File::timeAgo($data->postedOn); ?></span>
                                            </div>
                                            <div class="noti-footer-inner-right-text">
                                                <?php echo $getFromT->getTweetLinks($data->status); ?>
                                            </div>
                                        </div>
                                        <div class="noti-footer-inner-right">
                                            <?php if(!empty($data->tweetImage)): ?>
                                            <img src="<?php echo BASE_URL.$data->tweetImage; ?>" />
                                             <?php endif; ?>
                                        </div>

                                    </div>
                                    <!--END NOTIFICATION-inner-->
                                </div>
                            </div>
                        </div>
                        <!--NOTIFICATION WRAPPER END-->
                        <!-- Like Notification -->
                    <?php endif; ?>

                    <?php if($data->type == 'retweet') : ?>
                        <!-- Retweet Notification -->
                        <!--NOTIFICATION WRAPPER-->
                        <div class="notification-wrapper">
                            <div class="notification-inner">
                                <div class="notification-header">

                                    <div class="notification-img">
                                        <span class="retweet-logo">
                                            <i class="fa fa-retweet" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="notification-tweet">
                                        <a href="<?php echo BASE_URL.$data->username; ?>" class="notifi-name"><?php echo $data->screenName; ?></a><span> retweet your <?php if($data->tweetBy == $user_id){echo 'Tweet';}else{echo 'Retweet';} ?> - <span><?php echo File::timeAgo($data->time); ?></span>
                                    </div>
                                    <div class="notification-footer">
                                        <div class="noti-footer-inner">

                                            <div class="noti-footer-inner-left">
                                                <div class="t-h-c-name">
                                                    <span><a href="<?php echo BASE_URL.$user->username; ?>"><?php echo $user->screenName; ?></a></span>
                                                    <span>@<?php echo $user->username; ?></span>
                                                    <span><?php echo File::timeAgo($data->postedOn); ?></span>
                                                </div>
                                                <div class="noti-footer-inner-right-text">
                                                    <?php echo $getFromT->getTweetLinks($data->status); ?>
                                                </div>
                                            </div>


                                            <div class="noti-footer-inner-right">
                                            <?php if(!empty($data->tweetImage)): ?>
                                            <img src="<?php echo BASE_URL.$data->tweetImage; ?>" />
                                             <?php endif; ?>
                                            </div>

                                        </div>
                                        <!--END NOTIFICATION-inner-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--NOTIFICATION WRAPPER END-->
                        <!-- Retweet Notification -->
                    <?php endif; ?>

                    <?php if($data->type == 'mention') : ?>
                        <?php 
                                $tweet   = $data;
                                $retweet = $getFromT->checkRetweet($tweet->tweetID, $user_id);
                            	$likes   = $getFromT->likes($user_id, $tweet->tweetID);
                              
                                echo '  <div class="notification-wrapper">
                                <div class="notification-inner">
                                <div class="notification-header">
                                <div class="notification-img">
                                    <span class="heart-logo">
                                        <i class="fa fa-tag" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="notification-name">
                                    <div>
                                        <img src="'.BASE_URL.$tweet->profileImage.'" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="notification-tweet">
                                <a href="'.BASE_URL.$tweet->username.'" class="notifi-name"> '.$tweet->screenName.'</a><span> mentioned you - <span>'.File::timeAgo($tweet->time).'</span>
                            </div>
                                <div class="all-tweet-inners">
                <div class="t-show-wrap">	
                 <div class="t-show-inner">
                    <div class="t-show-popup" data-tweet="' . $tweet->tweetID . '">
                        <div class="t-show-head">
                            <div class="t-show-img">
                                <img src="' .BASE_URL. $tweet->profileImage . '"/>
                            </div>
                            <div class="t-s-head-content">
                                <div class="t-h-c-name">
                                    <span><a href="' .BASE_URL. $tweet->username . '">' . $tweet->screenName . '</a></span>
                                    <span>@' . $tweet->username . '</span>
                                    <span>' . File::timeAgo($tweet->postedOn) . '</span>
                                </div>
                                <div class="t-h-c-dis">
                                ' . Tweet::getTweetLinks($tweet->status) . '
                                </div>
                            </div>
                        </div>' .
                                    ((!empty($tweet->tweetImage)) ?
                                        '<!--tweet show head end-->
                            <div class="t-show-body">
                              <div class="t-s-b-inner">
                               <div class="t-s-b-inner-in">
                                 <img src="' . BASE_URL . $tweet->tweetImage . '" class="imagePopup" data-tweet="' . $tweet->tweetID . '"/>
                               </div>
                              </div>
                            </div>
                            <!--tweet show body end-->
                            ' : '') . '
                                    
                    </div>
        
                    <div class="t-show-footer">
                        <div class="t-s-f-right">
                            <ul> 
                            '.(($getFromU->isLoggedIn() === true) ? '
                                <li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>	
                                <li>' . (($tweet->tweetID === $retweet['retweetID'] or $user_id == $retweet['retweetBy']) ? '<button class="retweeted" data-tweet="' . $tweet->tweetID . '" data-user="' . $tweet->tweetBy . '"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">' . $tweet->retweetCount . '</span></button>' : '<button class="retweet" data-tweet="' . $tweet->tweetID . '" data-user="' . $tweet->tweetBy . '"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">' . (($tweet->retweetCount > 0) ? $tweet->retweetCount : '') . '</span></button>') . '</li>
                                <li>' . (($likes['likeOn']) === $tweet->tweetID ? '<button class="unlike-btn" data-tweet="' . $tweet->tweetID . '" data-user="' . $tweet->tweetBy . '"><i class="fa fa-heart" aria-hidden="true"></i><span class="likesCounter">' . $tweet->likesCount . '</span></button>' : '<button class="like-btn" data-tweet="' . $tweet->tweetID . '" data-user="' . $tweet->tweetBy . '"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="likesCounter">' . (($tweet->likesCount > 0) ? $tweet->likesCount : '') . '</span></button>') . '</li>
                               ' . (($tweet->tweetBy === $user_id) ? '
                                <li>
                                    <a href="#" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                                    <ul> 
                                      <li><label class="deleteTweet" data-tweet="' . $tweet->tweetID . '">Delete Tweet</label></li>
                                    </ul>
                                </li>' : '') . '
                                ' : '<li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>
                                    <li><button><i class="fa fa-retweet" aria-hidden="true"></i></button></li>
                                    <li><button><i class="fa fa-heart" aria-hidden="true"></i></button></li>
                                ').'
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
                </div>
                </div>
                </div>';
            ?>
                    <?php endif; ?> 
                        <?php endforeach; ?>
                    </div>
                    <!--NOTIFICATION WRAPPER FULL WRAPPER END-->

                    <div class="loading-div">
                        <img id="loader" src="<?php echo BASE_URL; ?>assets/images/loading.svg" style="display: none;" />
                    </div>
                    <div class="popupTweet"></div>
                    <!--Tweet END WRAPER-->
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/search.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/hashtag.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/like.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/retweet.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popuptweets.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/comment.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/delete.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popupForm.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/fetch.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/follow.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/postMessage.js"></script>
                    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/notification.js"></script>
                </div><!-- in left wrap-->
            </div><!-- in center end -->

            <div class="in-right">
                <div class="in-right-wrap">

                    <!--Who To Follow-->
                    <?php $getFromF->whoToFollow($user_id, $user_id); ?>
                    <!--Who To Follow-->

                </div><!-- in left wrap-->

            </div><!-- in right end -->
            <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/follow.js"></script>

        </div>
        <!--in full wrap end-->

    </div><!-- in wrappper ends-->
</div><!-- inner wrapper ends-->
</div><!-- ends wrapper -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/footer.php'; ?>