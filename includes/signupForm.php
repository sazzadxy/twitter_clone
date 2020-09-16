<?php
//include './core/init.php';

use TwitterClone\User\User;
use TwitterClone\Validate\Validate;

include $_SERVER['DOCUMENT_ROOT'].'/twitter_clone/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == "GET" && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))  {
	User::redirect('index.php');
  }

if (isset($_POST['signup'])) {
	$screenName = $_POST['screenName'];
	$email 		= $_POST['email'];
	$password	= $_POST['password'];

	if (empty($screenName and $email and $password)) {
		$error = "All fields are required!";
	} else {
		$screenName = Validate::escape($screenName);
		$email 		= Validate::escape($email);
		$password 	= Validate::escape($password);
		$error = '';
		

		if (!Validate::filterEmail($email)) {
			$error = "Invalid email format";
		} elseif (!Validate::validName($screenName)) {
			$error = "Use valid names";
		} elseif (Validate::length($screenName, 6, 20)) {
			$error = "Names should be between 6-20 characters long";
		} elseif (Validate::length($password, 6, 20)) {
			$error = "Password should be between 6-20 characters long";
		} else {
			if ($getFromU->checkEmail($email)) {
				$error = "Email is already taken!";
			} else {
				$hash = $getFromU->hash($password);
				$user_id = $getFromU->create('users', array('email' => $email, 'password' => $hash, 'screenName' => $screenName, 'username' => '', 'profileImage' => 'assets/images/defaultProfileimage.png',  'profileCover' => 'assets/images/defaultCoverImage.png', 'following' => 0, 'followers' => 0, 'bio' => '', 'country' => '', 'website' => ''));
				$_SESSION['user_id'] = $user_id;
				User::redirect('signup.php?step=1');

			}
		}
	}
}

?>

<form method="post">
<div class="signup-div"> 
	<h3>Sign up </h3>
	<ul>
		<li>
		    <input type="text" name="screenName" placeholder="Full Name"/>
		</li>
		<li>
		    <input type="email" name="email" placeholder="Email"/>
		</li>
		<li>
			<input type="password" name="password" placeholder="Password"/>
		</li>
		<li>
			<input type="submit" name="signup" Value="Signup for Twitter">
		</li>
	</ul>
	<?php if(isset($error)): ?>
	 <li class="error-li" style="list-style: none;">
	  <div class="span-fp-error"><?php echo $error; ?></div>
	 </li> 
	<?php endif; ?>
	
</div>
</form>