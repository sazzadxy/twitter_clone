<?php
//include '../vendor/autoload.php';

use TwitterClone\User\User;
use TwitterClone\Validate\Validate;

include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';
include $_SERVER['DOCUMENT_ROOT'].'/twitter_clone/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == "GET" && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))  {
	User::redirect('index.php');
  }

$user_id = $_SESSION['user_id'];
$user =   $getFromU->userData($user_id);

if($getFromU->isLoggedIn() === false) {
	User::redirect('index.php');
}

if (isset($_GET['step']) === true && empty($_GET['step']) === false) {
	if (isset($_POST['next'])) {
		$username = Validate::escape($_POST['username']);


		if (!empty($username)) {
			if (Validate::length($username, 4, 20)) {
				$error = "Username must be between 4-20 characters long";
			} elseif ($getFromU->checkUsername($username) === true) {
				$error = "Username is already taken!";
			} else {
				$getFromU->update('users', $user_id, array('username' => $username));
				User::redirect('signup.php?step=2');
			}
		} else {
			$error = "Please enter your desired username";
		}
	}
?>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/header.php'; ?>
	<div class="wrapper">
		<!-- nav wrapper -->
		<div class="nav-wrapper">

			<div class="nav-container">
				<div class="nav-second">
					<ul>
						<li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
					</ul>
				</div><!-- nav second ends-->
			</div><!-- nav container ends -->

		</div><!-- nav wrapper end -->

		<!---Inner wrapper-->
		<div class="inner-wrapper">
			<!-- main container -->
			<div class="main-container">
				<!-- step wrapper-->

				<?php if ($_GET['step'] == '1') { ?>
					<div class="step-wrapper">
						<div class="step-container">
							<form method="post">
								<h2>Choose a Username</h2>
								<h4>Don't worry, you can always change it later.</h4>
								<div>
									<input type="text" name="username" placeholder="Username" />
								</div>
								<div>
									<ul>
										<li>
											<?php if (isset($error)) {
												echo $error;
											} ?>
										</li>
									</ul>
								</div>
								<div>
									<input type="submit" name="next" value="Next" />
								</div>
							</form>
						</div>
					</div>
				<?php }  ?>


				<?php if ($_GET['step'] == '2') { ?>
					<div class='lets-wrapper'>
						<div class='step-letsgo'>
							<h2>We're glad you're here, <?php echo $user->screenName; ?></h2>
							<p>Tweety is a constantly updating stream of the coolest, most important news, media, sports, TV, conversations and more--all tailored just for you.</p>
							<br />
							<p>
								Tell us about all the stuff you love and we'll help you get set up.
							</p>
							<span>
								<a href='<?php echo BASE_URL; ?>home.php' class='backButton'>Let's go!</a>
							</span>
						</div>
					</div>
				<?php } ?>


			</div><!-- main container end -->

		</div><!-- inner wrapper ends-->
	</div><!-- ends wrapper -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/footer.php'; ?>
<?php } ?>