<?php

use TwitterClone\File\File;
use TwitterClone\User\User;
use TwitterClone\Validate\Validate;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';

$user_id = $_SESSION['user_id'];
$user    = $getFromU->userData($user_id);
$notify  = $getFromM->getNotificationCount($user_id);

if ($getFromU->isLoggedIn() === false) {
	User::redirect('index.php');
}

if (isset($_POST['submit'])) {

	$username = Validate::escape($_POST['username']);
	$email    = Validate::escape($_POST['email']);
	$error    = array();

	if (!empty($username) and !empty($email)) {
		if ($user->username != $username and $getFromU->checkUsername($username) === true) {
			$error['username'] = "Username is not available";
		} elseif (Validate::validUsername($username)) {
			$error['username'] = "Only characters and numbers are allowed";
		} elseif (!Validate::filterEmail($email)) {
			$error['email'] = "Invalid email format";
		} elseif ($user->email != $email and $getFromU->checkEmail($email) === true) {
			$error['email'] = "Email already in use";
		} else {
			$getFromU->update('users', $user_id, array('email' => $email, 'username' => $username));
			User::redirect('settings/account');
		}
	} else {
		$error['fields'] = "All fields are required";
	}
}


?>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/header.php'; ?>
<?php File::ch_title($user->screenName.' (@' . $user->username.') account edit page'); ?>
<div class="wrapper">
	<!-- header wrapper -->
	<div class="header-wrapper">

		<div class="nav-container">
			<!-- Nav -->
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/nav.php'; ?>
		</div><!-- nav ends -->


	</div><!-- header wrapper end -->

	<div class="container-wrap">

		<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/lefter.php'; ?>
		<!--LEFTER ENDS-->

		<div class="righter">
			<div class="inner-righter">
				<div class="acc">
					<div class="acc-heading">
						<h2>Account</h2>
						<h3>Change your basic account settings.</h3>
					</div>
					<div class="acc-content">
						<form method="POST">
							<div class="acc-wrap">
								<div class="acc-left">
									Username
								</div>
								<div class="acc-right">
									<input type="text" name="username" value="<?php echo $user->username; ?>" />
									<span>
										<?php if (isset($error['username'])) {
											echo $error['username'];
										} ?>
									</span>
								</div>
							</div>

							<div class="acc-wrap">
								<div class="acc-left">
									Email
								</div>
								<div class="acc-right">
									<input type="text" name="email" value="<?php echo $user->email; ?>" />
									<span>
										<?php if (isset($error['email'])) {
											echo $error['email'];
										} ?>
									</span>
								</div>
							</div>
							<div class="acc-wrap">
								<div class="acc-left">

								</div>
								<div class="acc-right">
									<input type="Submit" name="submit" value="Save changes" />
								</div>
								<div class="settings-error">
									<?php if (isset($error['fields'])) {
										echo $error['fields'];
									} ?>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="content-setting">
					<div class="content-heading">

					</div>
					<div class="content-content">
						<div class="content-left">
									
						</div>
						<div class="content-right">

						</div>
					</div>
				</div>
			</div>
		</div>
		<!--RIGHTER ENDS-->
		<div class="popupTweet"></div>
		    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/search.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/hashtag.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popupForm.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/delete.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/postMessage.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/notification.js"></script>
	</div>
	<!--CONTAINER_WRAP ENDS-->

</div><!-- ends wrapper -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/footer.php'; ?>