<?php

use TwitterClone\User\User;
use TwitterClone\File\File;
use TwitterClone\Tweet\Tweet;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';


if (isset($_GET['hashtag']) && !empty($_GET['hashtag'])) {
    $hashtag  = $getFromU->checkInput($_GET['hashtag']);
    $user_id  = @$_SESSION['user_id'];
    $user     = $getFromU->userData($user_id);
    $tweets   = $getFromT->getTweetsByHash($hashtag);
    $accounts = $getFromT->getUserByHash($hashtag);
    $notify   = $getFromM->getNotificationCount($user_id);
} else {
    User::redirect('index.php');
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/header.php'; ?>
<?php File::ch_title( '#' .$hashtag. ' hashtag on twetty'); ?>
<div class="wrapper">
	<!-- header wrapper -->
	<div class="header-wrapper">
		<div class="nav-container">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/nav.php' ?>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
		</div><!-- nav container ends -->
	</div><!-- header wrapper end -->
<!--#hash-header-->
<div class="hash-header">
	<div class="hash-inner">
		<h1><?php echo '#'.$hashtag; ?></h1>
	</div>
</div>	
<!--#hash-header end-->

<!--hash-menu-->
<div class="hash-menu">
	<div class="hash-menu-inner">
		<ul>
 			<li><a href="<?php echo BASE_URL.'hashtag/'.$hashtag; ?>">Latest</a></li>
			<li><a href="<?php echo BASE_URL.'hashtag/'.$hashtag.'?f=users'; ?>">Accounts</a></li>
			<li><a href="<?php echo BASE_URL.'hashtag/'.$hashtag.'?f=photos'; ?>">Photos</a></li>
  		</ul>
	</div>
</div>
<!--hash-menu-->
<!---Inner wrapper-->

<div class="in-wrapper">
	<div class="in-full-wrap">
		
		<div class="in-left">
			<div class="in-left-wrap">

               <!-- Who TO Follow -->
               <?php $getFromF->whoToFollow($user_id, $user_id); ?>

               <!--TRENDS-->
               <?php $getFromT->trends(); ?>
			   
			</div>
			<!-- in left wrap-->
		</div>
		<!-- in left end-->

<?php  if(strpos($_SERVER['REQUEST_URI'], '?f=photos')) : ?>
<!-- TWEETS IMAGES  -->
 <div class="hash-img-wrapper"> 
 	<div class="hash-img-inner"> 
         <?php foreach ($tweets as $tweet) {
             $retweet = $getFromT->checkRetweet($tweet->tweetID, $user_id);
             $likes   = $getFromT->likes($user_id, $tweet->tweetID);
             $user    = $getFromT->profileData($tweet->retweetBy);
             if (!empty($tweet->tweetImage)) {
                 echo ' <div class="hash-img-flex">
                 <img src="'.BASE_URL.$tweet->tweetImage.'" class="imagePopup" data-tweet="'.$tweet->tweetID.'"/>
                 <div class="hash-img-flex-footer">
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
            </div>';
             }
         } ?>
		
	</div>
</div> 
<!-- TWEETS IMAGES -->
<?php elseif(strpos($_SERVER['REQUEST_URI'], '?f=users')) :?>
<!--TWEETS ACCOUTS-->
<div class="wrapper-following">
<div class="wrap-follow-inner">
<?php foreach($accounts as $account): ?>
 <div class="follow-unfollow-box">
	<div class="follow-unfollow-inner">
		<div class="follow-background">
			<img src="<?php echo BASE_URL.$account->profileCover; ?>"/>
		</div>
		<div class="follow-person-button-img">
			<div class="follow-person-img">
			 	<img src="<?php echo BASE_URL.$account->profileImage; ?>"/>
			</div>
			<div class="follow-person-button">
			   <?php echo $getFromF->followBtn($account->user_id, $user_id, $user_id); ?>
			</div>
		</div>
		<div class="follow-person-bio">
			<div class="follow-person-name">
				<a href="<?php echo BASE_URL.$account->username; ?>"><?php echo $account->screenName; ?></a>
			</div>
			<div class="follow-person-tname">
				<a href="<?php echo BASE_URL.$account->username; ?>">@<?php echo $account->username; ?></a>
			</div>
			<div class="follow-person-dis">
            <?php echo $getFromT->getTweetLinks($account->bio); ?>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>

</div>
</div>
<!-- TWEETS ACCOUNTS -->
<?php else: ?>		
	 <div class="in-center">
		<div class="in-center-wrap">
        <!-- TWEETS -->
        <?php 	foreach ($tweets as $tweet) {
						$retweet = $getFromT->checkRetweet($tweet->tweetID, $user_id);
						$likes   = $getFromT->likes($user_id, $tweet->tweetID);
						$user    = $getFromT->profileData($tweet->retweetBy);
						echo '<div class="all-tweet">
		<div class="t-show-wrap">	
		 <div class="t-show-inner">
		 ' . (($retweet['retweetID'] === $tweet->retweetID or $tweet->retweetID > 0) ? '
			<div class="t-show-banner">
				<div class="t-show-banner-inner">
					<span><i class="fa fa-retweet" aria-hidden="true"></i></span><span>' . $user->screenName . ' Retweeted</span>
				</div>
			</div>
			' : '') . '

			' . ((!empty($tweet->retweetMsg) && $tweet->tweetID === $retweet['tweetID'] or $tweet->retweetID > 0) ? '
			<div class="t-show-popup" data-tweet="' . $tweet->tweetID . '">
			<div class="t-show-head">
			<div class="t-show-img">
				<img src="' . BASE_URL . $user->profileImage . '"/>
			</div>
			<div class="t-s-head-content">
				<div class="t-h-c-name">
					<span><a href="' . BASE_URL . $user->username . '">' . $user->screenName . '</a></span>
					<span>@' . $user->username . '</span>
					<span>' . File::timeAgo($tweet->postedOn) . '</span>
				</div>
				<div class="t-h-c-dis">
					' . Tweet::getTweetLinks($tweet->retweetMsg) . '
				</div>
			</div>
		</div>
		<div class="t-s-b-inner">
			<div class="t-s-b-inner-in">
				<div class="retweet-t-s-b-inner">
					' . ((!empty($tweet->tweetImage)) ? ' 
					<div class="retweet-t-s-b-inner-left">
						<img src="' . BASE_URL . $tweet->tweetImage . '" class="imagePopup" data-tweet="' . $tweet->tweetID . '"/>	
					</div>  ' : '') . '
					<div>
						<div class="t-h-c-name">
							<span><a href="' . BASE_URL . $tweet->username . '">' . $tweet->screenName . '</a></span>
							<span>@' . $tweet->username . '</span>
							<span>' . File::timeAgo($tweet->postedOn) . '</span>
						</div>
						<div class="retweet-t-s-b-inner-right-text">		
							' .Tweet::getTweetLinks($tweet->status)  . '
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
		' : '
			<div class="t-show-popup" data-tweet="' . $tweet->tweetID . '">
				<div class="t-show-head">
					<div class="t-show-img">
						<img src="' .BASE_URL. $tweet->profileImage . '"/>
					</div>
					<div class="t-s-head-content">
						<div class="t-h-c-name">
							<span><a href="' . $tweet->username . '">' . $tweet->screenName . '</a></span>
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
							
			</div>') . '

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
		</div>';
					} ?>
		</div>
	</div>
                <?php endif; ?>

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

	</div><!--in full wrap end-->
</div><!-- in wrappper ends-->

</div><!-- ends wrapper -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/footer.php'; ?>


