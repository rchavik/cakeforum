<div class="forum-path"> 
<?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » Lost password
</div>
<div class="forum-login">
<?php $session->flash(); ?>
<?php echo $form->create('User', array('action' => 'lostpassword')); ?>
<div class="field">
  <label>E-mail</label>
  <?php echo $form->text('User.email'); ?>
</div>
<div class="button">
  <?php echo $form->submit(); ?>
</div>
<?php echo $form->end(); ?>
</div>