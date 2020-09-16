<?php

use TwitterClone\User\User;
use TwitterClone\Validate\Validate;
use TwitterClone\File\File;
use TwitterClone\Tweet\Tweet;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
#include './core/init.php';

if ($getFromU->isLoggedIn() === false) {
	User::redirect('index.php');
}

$user_id = $_SESSION['user_id'];
$user    = $getFromU->userData($user_id);
$notify  = $getFromM->getNotificationCount($user_id);
// $imageError =  '';
// var_dump($imageError);

if (isset($_POST['screenName'])) {
	if (!empty($_POST['screenName'])) {
		$screenName = Validate::escape($_POST['screenName']);
		$bio = Validate::escape($_POST['bio']);
		$country = Validate::escape($_POST['country']);
		$website = Validate::escape($_POST['website']);

		if (Validate::length($screenName, 6, 20)) {
			$error = "Name must be between 6-20 characters long";
		} elseif (strlen($bio) > 120) {
			$error = "Description is too long!";
		} elseif (strlen($country) > 50) {
			$error = "Country name is too long!";
		} else {
			$getFromU->update('users', $user_id, array('screenName' => $screenName, 'bio' => $bio, 'country' => $country, 'website' => $website));
			User::redirect($user->username);
		}
	} else {
		$error = "Name field can't be blank";
	}
}

if (isset($_FILES['profileCover'])) {
	if (!empty($_FILES['profileCover']['name'][0])) {
		$fileRoot = File::uploadImage($_FILES['profileCover']);
		$getFromU->update('users', $user_id, array('profileCover' => $fileRoot));
		User::redirect($user->username);
	}
}

if (isset($_FILES['profileImage'])) {
	if (!empty($_FILES['profileImage']['name'][0])) {
		$fileRoot = File::uploadImage($_FILES['profileImage']);
		$getFromU->update('users', $user_id, array('profileImage' => $fileRoot));
		User::redirect($user->username);
	}
}


?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/header.php'; ?>
<?php File::ch_title($user->screenName.' (@' . $user->username.') edit page'); ?>
<div class="wrapper">
	<!-- header wrapper -->
	<div class="header-wrapper">

		<div class="nav-container">
			<!-- Nav -->
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/nav.php'; ?>
			<!-- nav ends -->
		</div>
		<!-- nav container ends -->
	</div>
	<!-- header wrapper end -->

	<!--Profile cover-->
	<div class="profile-cover-wrap">
		<div class="profile-cover-inner">
			<div class="profile-cover-img">
				<!-- PROFILE-COVER -->
				<img src="<?php echo BASE_URL . $user->profileCover; ?>" />

				<div class="img-upload-button-wrap">
					<div class="img-upload-button1">
						<label for="cover-upload-btn">
							<i class="fa fa-camera" aria-hidden="true"></i>
						</label>
						<span class="span-text1">
							Change your cover photo
						</span>
						<input id="cover-upload-btn" type="checkbox" />
						<div class="img-upload-menu1">
							<span class="img-upload-arrow"></span>
							<form method="post" enctype="multipart/form-data">
								<ul>
									<li>
										<label for="file-up">
											Upload photo
										</label>
										<input type="file" name="profileCover" onchange="this.form.submit();" id="file-up" />
									</li>
									<li>
										<label for="cover-upload-btn">
											Cancel
										</label>
									</li>
								</ul>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="profile-nav">
			<div class="profile-navigation">
				<ul>
					<li>
						<a href="#">
							<div class="n-head">
								TWEETS
							</div>
							<div class="n-bottom">
								<?php $getFromT->countTweets($user_id); ?>
							</div>
						</a>
					</li>
					<li>
						<a href="<?php echo BASE_URL.$user->username.'/following';?>">
							<div class="n-head">
								FOLLOWINGS
							</div>
							<div class="n-bottom">
								<?php echo $user->following; ?>
							</div>
						</a>
					</li>
					<li>
						<a href="<?php echo BASE_URL.$user->username.'/followers'; ?>">
							<div class="n-head">
								FOLLOWERS
							</div>
							<div class="n-bottom">
								<?php echo $user->followers; ?>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							<div class="n-head">
								LIKES
							</div>
							<div class="n-bottom">
								<?php $getFromT->countLikes($user_id); ?>
							</div>
						</a>
					</li>

				</ul>
				<div class="edit-button">
					<span>
						<button class="f-btn" type="button" value="Cancel" onclick="window.location.href='<?php echo BASE_URL . $user->username; ?>'">Cancel</button>
					</span>
					<span>
						<input type="submit" id="save" name="save" value="Save Changes">
					</span>

				</div>
			</div>
		</div>
	</div>
	<!--Profile Cover End-->

	<div class="in-wrapper">
		<div class="in-full-wrap">
			<div class="in-left">
				<div class="in-left-wrap">
					<!--PROFILE INFO WRAPPER END-->
					<div class="profile-info-wrap">
						<div class="profile-info-inner">
							<div class="profile-img">
								<!-- PROFILE-IMAGE -->
								<img src="<?php echo BASE_URL . $user->profileImage; ?>" />
								<div class="img-upload-button-wrap1">
									<div class="img-upload-button">
										<label for="img-upload-btn">
											<i class="fa fa-camera" aria-hidden="true"></i>
										</label>
										<span class="span-text">
											Change your profile photo
										</span>
										<input id="img-upload-btn" type="checkbox" />
										<div class="img-upload-menu">
											<span class="img-upload-arrow"></span>
											<form method="post" enctype="multipart/form-data">
												<ul>
													<li>
														<label for="profileImage">
															Upload photo
														</label>
														<input id="profileImage" type="file" onchange="this.form.submit();" name="profileImage" />

													</li>
													<li><a href="#">Remove</a></li>
													<li>
														<label for="img-upload-btn">
															Cancel
														</label>
													</li>
												</ul>
											</form>
										</div>
									</div>
									<!-- img upload end-->
								</div>
							</div>

							<form id="editForm" method="post" enctype="multipart/Form-data">
								<div class="profile-name-wrap">
									<?php if (isset($imageError)) : ?>
										<ul>
											<li class="error-li">
												<div class="span-pe-error"><?php echo $imageError; ?></div>

											</li>
										</ul>
									<?php endif; ?>

									<div class="profile-name">
										<input type="text" name="screenName" value="<?php echo $user->screenName; ?>" />
									</div>
									<div class="profile-tname">
										@<?php echo $user->username; ?>
									</div>
								</div>
								<div class="profile-bio-wrap">
									<div class="profile-bio-inner">
										<textarea class="status" placeholder="Bio" row="4" col="30" name="bio"><?php echo $user->bio; ?></textarea>
										<div class="hash-box">
											<ul>
											</ul>
										</div>
									</div>
								</div>
								<div class="profile-extra-info">
									<div class="profile-extra-inner">
										<ul>
											<li>
												<div class="profile-ex-location">
													<input id="cn" type="text" name="country" placeholder="Country" value="<?php echo $user->country; ?>" />
												</div>
											</li>
											<li>
												<div class="profile-ex-location">
													<input type="text" name="website" placeholder="Website" value="<?php echo $user->website; ?>" />
												</div>
											</li>

											<?php if (isset($error)) : ?>
												<li class="error-li">
													<div class="span-pe-error"><?php echo $error; ?></div>
												</li>
											<?php endif; ?>
							</form>

							<script type="text/javascript">
								$('#save').click(function() {
									$('#editForm').submit();
								})
							</script>
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
											<a href="#">0 Photos and videos </a>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<div class="profile-extra-footer-body">
							<ul>
								<!-- <li><img src="#"></li> -->
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
			$tweets = $getFromT->getUserTweets($user_id);

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
			<span>' . File::timeAgo($retweet['postedOn']) . '</span>
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
				<li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>	
				<li>' . (($tweet->tweetID === $retweet['retweetID'] or $user_id == $retweet['retweetBy']) ? '<button class="retweeted" data-tweet="' . $tweet->tweetID . '" data-user="' . $tweet->tweetBy . '"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">' . $tweet->retweetCount . '</span></button>' : '<button class="retweet" data-tweet="' . $tweet->tweetID . '" data-user="' . $tweet->tweetBy . '"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">' . (($tweet->retweetCount > 0) ? $tweet->retweetCount : '') . '</span></button>') . '</li>
				<li>' . (($likes['likeOn']) === $tweet->tweetID ? '<button class="unlike-btn" data-tweet="' . $tweet->tweetID . '" data-user="' . $tweet->tweetBy . '"><i class="fa fa-heart" aria-hidden="true"></i><span class="likesCounter">' . $tweet->likesCount . '</span></button>' : '<button class="like-btn" data-tweet="' . $tweet->tweetID . '" data-user="' . $tweet->tweetBy . '"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="likesCounter">' . (($tweet->likesCount > 0) ? $tweet->likesCount : '') . '</span></button>') . '</li>
			   ' . (($tweet->tweetBy === $user_id) ? '
				<li>
					<a href="" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
					<ul> 
					  <li><label class="deleteTweet" data-tweet="' . $tweet->tweetID . '">Delete Tweet</label></li>
					</ul>
				</li>
				' : '') . '
			</ul>
		</div>
	</div>
</div>
</div>
</div>';
			}
			
			
			?>
		</div>
		<!-- in left wrap-->
		<div class="popupTweet"></div>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/search.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/hashtag.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/like.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/retweet.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popuptweets.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/comment.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/delete.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popupForm.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/postMessage.js"></script>
				<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/notification.js"></script>

	</div>
	<!-- in center end -->

	<div class="in-right">
		<div class="in-right-wrap">
			<!--==WHO TO FOLLOW==-->
			<?php $getFromF->whoToFollow($user_id, $user_id); ?>
			<!--==WHO TO FOLLOW==-->
			
			<!--==TRENDS==-->
			<?php $getFromT->trends(); ?>
			<!--==TRENDS==-->
		</div>
		<!-- in left wrap-->
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
