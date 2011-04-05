<div class="forum-path">
<?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » 
<?php echo $html->link($forumName, array('controller' => 'forum', 'action' => 'view', $forumId)); ?> » 
<?php echo $html->link($topicSubject, array('controller' => 'forum', 'action' => 'topic', $topicId, $forumId)); ?>
</div>
<div class="forum-topic-title">Reply</div>
<form action="" method="post" id="newtopic">
  <?php echo $form->hidden('ForumTopic.forum_category_id'); ?>
  <div class="field">
    <label>Message</label>
    <?php echo $form->textarea('ForumPost.text', array('rows' => 10)); ?>
    <div class="error-message"> <?php echo $form->error('ForumPost.text', 'Message is required!'); ?> </div>
  </div>
  <div class="button">
    <input name="submit" type="submit" value="Submit" />
  </div>
</form>
