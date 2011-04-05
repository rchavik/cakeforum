<?php

	Router::connect('/', array('controller' => 'forum', 'action' => 'index'));
	
	// Users
	
	Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
	Router::connect('/registered', array('controller' => 'users', 'action' => 'registered'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/account', array('controller' => 'users', 'action' => 'account'));
	Router::connect('/lostpassword', array('controller' => 'users', 'action' => 'lostpassword'));
	Router::connect('/user/*', array('controller' => 'users', 'action' => 'profile'));
	
	// Forum
	
	Router::connect('/view/*', array('controller' => 'forum', 'action' => 'view'));
	Router::connect('/topic/*', array('controller' => 'forum', 'action' => 'topic'));
	Router::connect('/newtopic/*', array('controller' => 'forum', 'action' => 'newtopic'));
	Router::connect('/reply/*', array('controller' => 'forum', 'action' => 'reply'));
	Router::connect('/editpost/*', array('controller' => 'forum', 'action' => 'editpost'));
	Router::connect('/deletepost/*', array('controller' => 'forum', 'action' => 'deletepost'));
	Router::connect('/unanswered', array('controller' => 'forum', 'action' => 'unanswered'));
	
?>