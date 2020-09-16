<?php

use TwitterClone\User\User;
use TwitterClone\File\File;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';

$user_id = $_SESSION['user_id'];
$user = $getFromU->userData($user_id);
$hash = $user->password;
$notify  = $getFromM->getNotificationCount($user_id);

if ($getFromU->isLoggedIn() === false) {
	User::redirect('index.php');
}

if (isset($_POST['submit'])) {
	$currentPwd  = $_POST['currentPwd'];
	$newPassword = $_POST['newPassword'];
	$rePassword  = $_POST['rePassword'];
	$error       = array();


	if (!empty($currentPwd) && !empty($newPassword) && !empty($rePassword)) {
		if (password_verify($currentPwd, $hash)) {
			if (strlen($newPassword) < 6) {
				$error['newPassword'] = "Password is too short!";
			} elseif ($newPassword != $rePassword) {
				$error['rePassword'] = "Password does not match!";
			} elseif ($currentPwd === $rePassword) {
				$error['newPassword'] = "Choose a new passworrd!";
			} else {
				$hash = $getFromU->hash($newPassword);
				$getFromU->update('users', $user_id, array('password' => $hash));
				User::redirect($user->username);
			}
		} else {
			$error['currentPwd'] = "Password is incorrect!";
		}
	} else {
		$error['fields'] = "Every fields is required!";
	}
}

?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/header.php'; ?>
<?php File::ch_title($user->screenName.' (@' . $user->username.') password edit page'); ?>
<div class="wrapper">
	<!-- header wrapper -->
	<div class="header-wrapper">

		<div class="nav-container">
			<!-- Nav -->
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/nav.php'; ?>
		</div><!-- nav container ends -->

	</div><!-- header wrapper end -->
	<div class="container-wrap">
		<?php include 'includes/lefter.php'; ?>
		<!--LEFTER ENDS-->

		<div class="righter">
			<div class="inner-righter">
				<div class="acc">
					<div class="acc-heading">
						<h2>Password</h2>
						<h3>Change your password or recover your current one.</h3>
					</div>
					<form method="POST">
						<div class="acc-content">
							<div class="acc-wrap">
								<div class="acc-left">
									Current password
								</div>
								<div class="acc-right">
									<input type="password" name="currentPwd" />
									<span>
										<?php if (isset($error['currentPwd'])) {
											echo $error['currentPwd'];
										} ?>
									</span>
								</div>
							</div>

							<div class="acc-wrap">
								<div class="acc-left">
									New password
								</div>
								<div class="acc-right">
									<input type="password" name="newPassword" />
									<span>
										<?php if (isset($error['newPassword'])) {
											echo $error['newPassword'];
										} ?>
									</span>
								</div>
							</div>

							<div class="acc-wrap">
								<div class="acc-left">
									Verify password
								</div>
								<div class="acc-right">
									<input type="password" name="rePassword" />
									<span>
										<?php if (isset($error['rePassword'])) {
											echo $error['rePassword'];
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
</div>
<div class="popupTweet"></div>	
        <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/search.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/hashtag.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popupForm.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/delete.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/postMessage.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/notificaton.js"></script>
<!--CONTAINER_WRAP ENDS-->
</div>
<!-- ends wrapper -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/footer.php'; ?>