
<div class="nav">
	<div class="nav-left">
		<ul>
			<li><a href="<?php echo BASE_URL; ?>home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
			<?php if ($getFromU->isLoggedIn() === true) : ?>
				<li><a href="<?php echo BASE_URL; ?>i/notifications"><i class="fa fa-bell" aria-hidden="true"></i>Notification<span id="notification"><?php if($notify->totalN > 0){echo '<span class="span-i">'.$notify->totalN.'</sapn>';} ?></span></a></li>
				<li id="messagePopup"><i class="fa fa-envelope" aria-hidden="true"></i>Messages<span id="messages"><?php if($notify->totalM > 0){echo '<span class="span-i">'.$notify->totalM.'</sapn>';} ?></span></li>
			<?php endif; ?>
		</ul>
	</div><!-- nav left ends-->

	<div class="nav-right">
		<ul>
			<li>
				<input type="text" placeholder="Search" class="search" />
				<i class="fa fa-search" aria-hidden="true"></i>
				<div class="search-result">
				</div>
			</li>

            <?php if($getFromU->isLoggedIn() === true){  ?>
			<li class="hover"><label class="drop-label" for="drop-wrap1"><img src="<?php echo BASE_URL . $user->profileImage; ?>" /></label>
				<input type="checkbox" id="drop-wrap1">
				<div class="drop-wrap">
					<div class="drop-inner">
						<ul>
							<li><a href="<?php echo BASE_URL . $user->username; ?>"><?php echo $user->username; ?></a></li>
							<li><a href="<?php echo BASE_URL; ?>settings/account">Settings</a></li>
							<li><a href="<?php echo BASE_URL; ?>includes/logout.php">Log out</a></li>	
						</ul>
					</div>
				</div>
			</li>
			<li><label for="pop-up-tweet" class="addTweetBtn">Tweet</label></li>
			<?php } else {
				echo '<li><label for="join" class="join"><a href="'.BASE_URL.'index.php" style="color:#fff;">Join</a></label></li>';
			}?>
		</ul>
	</div>
</div>
