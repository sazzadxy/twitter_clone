<?php

use TwitterClone\User\User;

include  $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/core/init.php';

if (isset($_SESSION['user_id'])) {
	User::redirect('home.php');
}

?>


<?php include 'includes/header.php'; ?>
<div class="front-img">
	<img src="<?php echo BASE_URL; ?>assets/images/background.jpg"/>
</div>	

<div class="wrapper">
<!-- header wrapper -->
<div class="header-wrapper">
	
	<div class="nav-container">
		<!-- Nav -->
		<div class="nav">
			
			<div class="nav-left">
				<ul>
					<li><i class="fa fa-twitter" aria-hidden="true"></i><a href="#">Home</a></li>
					<li><a href="#">About</a></li>
				</ul>
			</div><!-- nav left ends-->

			<div class="nav-right">
				<ul>
					<li><a href="#">Language</a></li>
				</ul>
			</div><!-- nav right ends-->

		</div><!-- nav ends -->

	</div><!-- nav container ends -->

</div><!-- header wrapper end -->
	
<!---Inner wrapper-->
<div class="inner-wrapper">
	<!-- main container -->
	<div class="main-container">
		<!-- content left-->
		<div class="content-left">
			<h1>Welcome to Tweety.</h1>
			<br/>
			<p>A place to connect with your friends — and Get updates from the people you love, And get the updates from the world and things that interest you.</p>
		</div><!-- content left ends -->	

		<!-- content right ends -->
		<div class="content-right">
			<!-- Log In Section -->
			<div class="login-wrapper">
			  <?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/login.php'; ?>
			</div><!-- log in wrapper end -->

			<!-- SignUp Section -->
			<div class="signup-wrapper">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/signupForm.php'; ?>
			</div>
			<!-- SIGN UP wrapper end -->

		</div><!-- content right ends -->

	</div><!-- main container end -->

</div><!-- inner wrapper ends-->
</div><!-- ends wrapper -->

<?php include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/includes/footer.php'; ?>