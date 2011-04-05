 <div class="forum-path"><?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » Register</div>
<div class="forum-login">
	<?php echo $form->create('User', array('action' => 'register')); ?>
    <div class="field">
      <label>Username</label>
	  <?php echo $form->text('User.username'); ?>
	  <?php echo $form->error('User.username', array(
							 	'required' => 'Username is required!',
								'alphanumeric' => 'Username is invalid.',
								'checkunique'  => 'Username is already in use.'
 							)); 
	  ?>
    </div>
    <div class="field">
      <label>E-mail</label>
      <?php echo $form->text('User.email'); ?>
	  <?php echo $form->error('User.email', array(
							 	'required' => 'E-mail is required!',
								'validemail' => 'E-mail is invalid.',
								'checkunique'  => 'E-mail is already in use.'
 							)); 
	  ?>      
    </div>
    <div class="field">
      <label>Password</label>
      <?php echo $form->password('User.form_password'); ?>
	  <?php echo $form->error('User.form_password', array(
							 	'required' => 'Password is required!',
								'lenght'  => 'The password must be at least 3 characters long.'
 							)); 
	  ?>      
    </div>
    <div class="field">
      <label>Password confirm</label>
      <?php echo $form->password('User.confirm_password'); ?>
	  <?php echo $form->error('User.confirm_password', array(
							 	'required' => 'Password confirm is required!',
								'lenght'  => 'The password must be at least 3 characters long.',
								'confirm' => 'Confirm password.'
 							)); 
	  ?>      
      </div>
   
    <div class="button">
      <input name="register" type="submit" value="Register" id="register" />
    </div>
    <?php echo $form->end(); ?>
	</div>