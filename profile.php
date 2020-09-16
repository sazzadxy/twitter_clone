<?php

use TwitterClone\User\User;
use TwitterClone\Validate\Validate;
use TwitterClone\File\File;
use TwitterClone\Tweet\Tweet;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';

// if($getFromU->isLoggedIn() === true) {
// 	$user_id = $_SESSION['user_id'];
// 	$user    = $getFromU->userData($user_id);
// } 

if (isset($_GET['username']) === true && empty($_GET['username']) === false) {
	$username       = Validate::escape($_GET['username']);
	$profileId      = $getFromU->userIdbyUsername($username);
	$profileData    = $getFromU->userData($profileId);
	$user_id        = @$_SESSION['user_id'];
	$user           = $getFromU->userData($user_id);
	$notify         = $getFromM->getNotificationCount($user_id);

	if (!$profileData) {
		User::redirect('index.php');
	}
}

?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/header.php'; ?>
<?php File::ch_title($profileData->screenName.' (@' . $profileData->username.')'); ?>
<div class="wrapper">
	<!-- header wrapper -->
	<div class="header-wrapper">
		<div class="nav-container">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/nav.php' ?>
		</div><!-- nav container ends -->
	</div><!-- header wrapper end -->
	<!--Profile cover-->
	<div class="profile-cover-wrap">
		<div class="profile-cover-inner">
			<div class="profile-cover-img">
				<!-- PROFILE-COVER -->
				<img src="<?php echo BASE_URL . $profileData->profileCover; ?>" />
			</div>
		</div>
		<div class="profile-nav">
			<div class="profile-navigation">
				<ul>
					<!-- <li>
					<a href="<?php echo BASE_URL . $profileData->username; ?>">
						<div class="n-head">
						<a href="<?php echo BASE_URL . $profileData->username; ?>">TWEETS</a>
						</div>
						<div class="n-bottom">
						<span class="count-following"><?php echo $getFromT->countTweets($profileId); ?></span>
						</div>
					</a>	
					</li> -->
					<li>
						<a href="<?php echo BASE_URL . $profileData->username; ?>/tweets">
							<div class="n-head">
								<a href="<?php echo BASE_URL . $profileData->username; ?>">TWEETS</a>
							</div>
							<div class="n-bottom">
								<span class="count-following"><?php echo $getFromT->countTweets($profileId); ?></span>
							</div>
						</a>
					</li>
					<li>
						<a href="<?php echo BASE_URL . $profileData->username; ?>/following">
							<div class="n-head">
								<a href="<?php echo BASE_URL . $profileData->username; ?>/following">FOLLOWING</a>
							</div>
							<div class="n-bottom">
								<span class="count-following"><?php echo $profileData->following; ?></span>
							</div>
						</a>
					</li>
					<li>
						<a href="<?php echo BASE_URL . $profileData->username; ?>/followers">
							<div class="n-head">
								FOLLOWERS
							</div>
							<div class="n-bottom">
								<span class="count-followers"><?php echo $profileData->followers; ?></span>
							</div>
						</a>
					</li>
					<li>
						<a href="<?php echo BASE_URL . $profileData->username; ?>">
							<div class="n-head">
								LIKES
							</div>
							<div class="n-bottom">
							<span class="count-likes">
								<?php
								echo (!empty($getFromT->countLikes($profileId))) ? "'.$getFromT->countLikes($profileId).'" : "" ;
								?>
								</span>
						
							</div>
						</a>
					</li>
				</ul>
				<div class="edit-button">
					<span>
						 <?php echo $getFromF->followBtn($profileId, $user_id, $profileData->user_id); ?>
					</span>
				</div>
			</div>
		</div>
	</div>
	<!--Profile Cover End-->

	<!---Inner wrapper-->
	<div class="in-wrapper">
		<div class="in-full-wrap">
			<div class="in-left">
				<div class="in-left-wrap">
					<!--PROFILE INFO WRAPPER END-->
					<div class="profile-info-wrap">
						<div class="profile-info-inner">
							<!-- PROFILE-IMAGE -->
							<div class="profile-img">
								<img src="<?php echo BASE_URL . $profileData->profileImage; ?>" />
							</div>

							<div class="profile-name-wrap">
								<div class="profile-name">
									<a href="<?php echo BASE_URL . $profileData->username; ?>"><?php echo $profileData->screenName; ?></a>
								</div>
								<div class="profile-tname">
									@<span class="username"><?php echo $profileData->username; ?></span>
								</div>
							</div>

							<div class="profile-bio-wrap">
								<div class="profile-bio-inner">
									<?php echo $profileData->bio; ?>
								</div>
							</div>

							<div class="profile-extra-info">
								<div class="profile-extra-inner">
									<ul>
										<?php if (!empty($profileData->country)) : ?>
											<li>
												<div class="profile-ex-location-i">
													<i class="fa fa-map-marker" aria-hidden="true"></i>
												</div>
												<div class="profile-ex-location">
													<?php echo $profileData->country; ?>
												</div>
											</li>
										<?php endif; ?>
										<?php if (!empty($profileData->website)) : ?>
											<li>
												<div class="profile-ex-location-i">
													<i class="fa fa-link" aria-hidden="true"></i>
												</div>
												<div class="profile-ex-location">
													<a href="<?php echo $profileData->website; ?>" target="_blank"><?php echo $profileData->website; ?></a>
												</div>
											</li>
										<?php endif; ?>

										<li>
											<div class="profile-ex-location-i">
												<!-- <i class="fa fa-calendar-o" aria-hidden="true"></i> -->
											</div>
											<div class="profile-ex-location">
											</div>
										</li>
										<li>
											<div class="profile-ex-location-i">
												<!-- <i class="fa fa-tint" aria-hidden="true"></i> -->
											</div>
											<div class="profile-ex-location">
											</div>
										</li>
									</ul>
								</div>
							</div>

							<div class="profile-extra-footer">
								<div class="profile-extra-footer-head">
									<div class="profile-extra-info">
										<ul>
											<li>
												<div class="profile-ex-location-i">
													<i class="fa fa-camera" aria-hidden="true"></i>
												</div>
												<div class="profile-ex-location">
													<a href="#">0 Photos </a>
												</div>
												<div class="profile-ex-location-i">
													<i class="fa fa-video-camera" aria-hidden="true"></i>
												</div>
												<div class="profile-ex-location">
													<a href="#">0 Videos </a>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<div class="profile-extra-footer-body">
									<ul>
										<!-- <li><img src="#"/></li> -->
									</ul>
								</div>
							</div>

						</div>
						<!--PROFILE INFO INNER END-->

					</div>
					<!--PROFILE INFO WRAPPER END-->

				</div>
				<!-- in left wrap-->

			</div>
			<!-- in left end-->

			<div class="in-center">
				<div class="in-center-wrap">
					<?php

					$tweets = $getFromT->getUserTweets($profileId);
					foreach ($tweets as $tweet) {
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
					}


					?>
				</div><!-- in left wrap-->
				<div class="popupTweet"></div>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/search.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/hashtag.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/like.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/retweet.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popuptweets.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/comment.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/delete.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popupForm.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/fetch.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/postMessage.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/notification.js"></script>
			</div>
			<!-- in center end -->

			<div class="in-right">
				<div class="in-right-wrap">

					<!--==WHO TO FOLLOW==-->
					<?php $getFromF->whoToFollow($user_id, $profileId); ?>
					<!--==WHO TO FOLLOW==-->

					<!--==TRENDS==-->
					<?php $getFromT->trends(); ?>
					<!--==TRENDS==-->

				</div><!-- in right wrap-->
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/follow.js"></script>
			</div>
			<!-- in right end -->

		</div>
		<!--in full wrap end-->
	</div>
	<!-- in wrappper ends-->
</div>
<!-- ends wrapper -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/footer.php'; ?>