<?php

use TwitterClone\User\User;
use TwitterClone\Validate\Validate;
use TwitterClone\File\File;

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
	$user_id        = $_SESSION['user_id'];
	$user           = $getFromU->userData($user_id);
	$notify         = $getFromM->getNotificationCount($user_id);

	if ($getFromU->isLoggedIn() === false) {
		User::redirect('index.php');
	}

	if (!$profileData) {
		User::redirect('index.php');
	}

} else {
	User::redirect('index.php');
}

?>


<?php include 'includes/header.php'; ?>
<?php File::ch_title('People following '.$profileData->screenName.'  @' . $profileData->username); ?>
<div class="wrapper">
	<!-- header wrapper -->
	<div class="header-wrapper">
		<div class="nav-container">
			<?php include 'includes/nav.php' ?>
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
					<li>
						<div class="n-head">
							TWEETS
						</div>
						<div class="n-bottom">
							<?php echo $getFromT->countTweets($profileId); ?>
						</div>
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
						<a href="">
							<div class="n-head">
								LIKES
							</div>
							<div class="n-bottom">
								<?php echo $getFromT->countLikes($profileId); ?>
							</div>
						</a>
					</li>
				</ul>
				<div class="edit-button">
					<span>
						<?php echo $getFromF->followBtn($profileId, $user_id, $profileData->user_id) ?>
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
								<!-- whoToFollow -->
								<?php $getFromF->whoToFollow($user_id, $user_id); ?>
								<!-- trends -->
								<?php $getFromT->trends(); ?>
							</div>

						</div>
						<!--PROFILE INFO INNER END-->

					</div>
					<!--PROFILE INFO WRAPPER END-->

					<div class="popupTweet"></div>
				</div>
				<!-- in left wrap-->
			</div>
			<!-- in left end-->
			<!--FOLLOWING OR FOLLOWER FULL WRAPPER-->
			<div class="wrapper-following">
				<div class="wrap-follow-inner">
					<?php echo $getFromF->followersList($profileId, $user_id, $profileData->user_id) ?>
					<?php $getFromT->trends(); ?>
				</div>
				<!-- wrap follo inner end-->
			</div>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/follow.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/search.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/hashtag.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/like.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/retweet.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popuptweets.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/comment.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popupForm.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/fetch.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/postMessage.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/notification.js"></script>
			<!--FOLLOWING OR FOLLOWER FULL WRAPPER END-->
		</div>
		<!--in full wrap end-->
	</div>
	<!-- in wrappper ends-->
</div><!-- ends wrapper -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/footer.php'; ?>