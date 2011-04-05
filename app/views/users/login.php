<div class="forum-path"> 
<?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » Login
</div>
<div class="forum-login">
	<?php $session->flash(); ?>
	<?php echo $form->create('User', array('action' => 'login')); ?>
    <div class="field">
      <label>Username</label>
      <?php echo $form->text('User.username'); ?>
    </div>
    <div class="field">
      <label>Password</label>
      <?php echo $form->password('User.password'); ?>
    </div>
        <div class="button">
      <?php echo $form->submit('Login'); ?>
    </div>
    <div class="field"><?php echo $html->link('Lost password?', array('controller' => 'users', 'action' => 'lostpassword')); ?></div>
    <?php echo $form->end(); ?>
  </div>
