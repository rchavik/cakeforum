<div class="forum-path">
<?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » 
<?php echo $html->link($forumName, array('controller' => 'forum', 'action' => 'view', $forumID)); ?> » 
<?php echo $html->link($topicSubject, array('controller' => 'forum', 'action' => 'topic', $topicID, $forumID)); ?>
</div>
<div class="forum-topic-title">Edit topic</div>
<form action="" method="post" id="newtopic">
  <div class="field">
    <label>Subject</label>
    <?php echo $form->text('ForumTopic.subject', array('rows' => 10)); ?>
    <div class="error-message"><?php echo $form->error('ForumTopic.subject', 'Topic subject is required!'); ?> </div>
  </div>
  <div class="field">
    <label>Message</label>
    <?php echo $form->textarea('ForumPost.text', array('rows' => 10)); ?>
    <div class="error-message"> <?php echo $form->error('ForumPost.text', 'Message is required'); ?> </div>
  </div>
  <div class="button">
    <input name="submit" type="submit" value="Submit" />
  </div>
</form>