<?php

use TwitterClone\File\File;
use TwitterClone\User\User;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';

$user_id =  $_SESSION['user_id'];
$user    = $getFromU->userData($user_id);
$notify  = $getFromM->getNotificationCount($user_id);

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

		$tweet_id = $getFromU->create('tweets', array('status' => $status, 'tweetBy' => $user_id, 'tweetImage' => $tweetImage, 'retweetID' => 0, 'retweetBy' => 0, 'likesCount' => 0, 'retweetCount' => 0, 'retweetMsg' => '', 'postedOn' => date('Y-m-d H:i:s')));
		preg_match_all("/#+([a-zA-Z0-9_]+)/i", $status, $hashtag);

		if (!empty($hashtag)) {
			$getFromT->addTrend($status);
		}
		$getFromT->addMention($status, $user_id, $tweet_id);

		User::redirect('home.php');
	} else {
		$error = "Type or choose image to tweet";
	}
}



?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/header.php'; ?>
<?php File::ch_title( 'twetty feed'); ?>
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
								<img src="<?php echo BASE_URL.$user->profileCover; ?>" />
							</div><!-- info in head end -->
							<div class="info-in-body">
								<div class="in-b-box">
									<div class="in-b-img">
										<!-- PROFILE-IMAGE -->
										<img src="<?php echo BASE_URL.$user->profileImage; ?>" />
									</div>
								</div><!--  in b box end-->
								<div class="info-body-name">
									<div class="in-b-name">
										<div><a href="<?php echo BASE_URL.$user->username; ?>"><?php echo $user->screenName; ?></a></div>
										<span><small><a href="<?php echo BASE_URL.$user->username; ?>">@<?php echo $user->username; ?></a></small></span>
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
					<!--TWEET WRAPPER-->
					<div class="tweet-wrap">
						<div class="tweet-inner">
							<div class="tweet-h-left">
								<div class="tweet-h-img">
									<!-- PROFILE-IMAGE -->
									<img src="<?php echo $user->profileImage; ?>" />
								</div>
							</div>
							<div class="tweet-body">
								<form method="post" enctype="multipart/form-data">
									<textarea class="status" maxlength="140" name="status" placeholder="Post your tweet!" rows="4" cols="50"></textarea>
									<div class="hash-box">
										<ul>
										</ul>
									</div>
							</div>
							<div class="tweet-footer">
								<div class="t-fo-left">
									<ul>
										<input type="file" name="file" id="file" />
										<li><label for="file">
												<i class="fa fa-camera" aria-hidden="true"> </i>
												<i class="fa fa-video-camera" aria-hidden="true"></i>
											</label>
											<span class="tweet-error"><?php if (isset($error)) {
																			echo $error;
																		} elseif (isset($imageError)) {
																			echo $imageError;
																		} ?></span>

										</li>
									</ul>
								</div>
								<div class="t-fo-right">
									<span id="count">140</span>
									<input type="submit" name="tweet" value="Tweet" />
									</form>
								</div>
							</div>
						</div>
						<script src="<?php echo BASE_URL; ?>assets/js/count.js"></script>
					</div>
					<!--TWEET WRAP END-->


					<!--Tweet SHOW WRAPPER-->
					<div class="tweets">
						<?php $getFromT->tweets($user_id, 10); ?>
					</div>
					<!--TWEETS SHOW WRAPPER-->

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