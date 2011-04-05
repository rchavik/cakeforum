<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CakeForum » <?php echo $title_for_layout?></title>
<?php echo $html->css('forum');?>
</head>
<body>
<div class="container">
<div class="header">
<?php echo $html->image('logo.jpg', array('alt' => 'CakeForum'))?>
</div>
<div class="nav">
<?php
	if($user->isLogged()) {
		echo "<span>Hello " . $user->username() . "</span>";
		echo $html->link('Account', array('controller' => 'users', 'action' => 'account'));
		echo "  ";
		echo $html->link('Logout', array('controller' => 'users', 'action' => 'logout'));
	} else {
		echo $html->link('Register', array('controller' => 'users', 'action' => 'register'));
		echo "  ";
		echo $html->link('Login', array('controller' => 'users', 'action' => 'login'));
	}
?>
</div>
<?php echo $content_for_layout;?>
<div class="footer">Developed by <a href="http://www.inservio.ba">Inservio</a></div>
</div>
</body>
</html>