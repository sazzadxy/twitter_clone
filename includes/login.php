<?php
include $_SERVER['DOCUMENT_ROOT'].'/twitter_clone/vendor/autoload.php';
use TwitterClone\User\User;
use TwitterClone\Validate\Validate;


if ($_SERVER['REQUEST_METHOD'] == "GET" && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))  {
  User::redirect('index.php');
}
if (isset($_POST['login']) && !empty($_POST['login'])) {
  $email = Validate::escape($_POST['email']);
  $password = Validate::escape($_POST['password']);

  //$password = md5($password);
  if (!empty($email) && !empty($password)) {
    //$email = $getFromU->checkInput($email);
    //$password = $getFromU->checkInput($password);
    if (!Validate::filterEmail($email)) {
      $errors = "Invalid email!";
    } else {
      if ($user = $getFromU->emailExists($email)) {
        $hash = $user->password;
        if (password_verify($password, $hash)) {
          $_SESSION['user_id'] = $user->user_id;
          User::redirect('home.php');
        } else {
          $errors = "Email or Password is incorrect!";
        }
      } else {
        $errors = "No account with this email exists";
      }
    }
  } else {
    $errors = "Please enter your email and password";
  }
}


?>

<div class="login-div">
  <form method="post">
    <ul>
      <li>
        <input type="text" name="email" placeholder="Please enter your Email here" />
      </li>
      <li>
        <input type="password" name="password" placeholder="password" style="width: 92%;" />
      </li>
      <li>
        <input type="checkbox" Value="Remember me" style="margin: 4px;">Remember me
        <input type="submit" name="login" value="Log in" />
      </li>
    </ul>


    <!-- <?php if (isset($error)) {
      echo '<li class="error-li" style="list-style: none;">
	  <div class="span-fp-error">' . $error . '</div>
     </li> ';
    } ?> -->

    <?php
    if (isset($errors)) : ?>
      <li class="error-li" style="list-style: none;">
        <div class="span-fp-error"><?php echo $errors; ?></div>
      </li>
    <?php endif; ?>


  </form>
</div>