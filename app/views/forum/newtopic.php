<div class="forum-path"> 
<?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » 
<?php echo $html->link($forumName, array('controller' => 'forum', 'action' => 'view', $forumId)); ?> 
</div>
<div class="forum-topic-title">Add new topic</div>

<form action="" method="post" id="newtopic">
<?php //echo $form->create('ForumTopic'); ?>
<div class="field">
      <label>Subject</label>
      <?php echo $form->text('ForumTopic.subject'); ?>
      <div class="error-message">
	  	<?php echo $form->error('ForumTopic.subject', 'Subject is required!'); ?>
      </div>
    </div>
<div class="field">
      <label>Message</label>
      <?php echo $form->textarea('ForumTopic.text', array('rows' => 10)); ?>
      <div class="error-message">
	  	<?php echo $form->error('ForumTopic.text', 'Message is required'); ?>
      </div>
    </div>
    <div class="button">
      <input name="register" type="submit" value="Submit" />
    </div>
    </form>
<?php //echo $form->end('ForumTopic'); ?>




