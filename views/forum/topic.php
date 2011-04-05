<div class="forum-path"> 
<?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » 
<?php echo $html->link($forumName, array('controller' => 'forum', 'action' => 'view', $forumId)); ?> 
</div>
<div class="forum-topic-title"><?php echo $topic['ForumTopic']['subject'] ?></div>
<div class="forum-index">
  <table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th width="144">Author</th>
      <th width="615">Message</th>
    </tr>
    <?php foreach ($posts as $post): ?>
    <tr>
    	<a name="<?php echo $post['ForumPost']['id']; ?>" id="<?php echo $post['ForumPost']['id']; ?>"></a>
      <td class="leftalign"><?php echo $html->link($post['User']['username'], 
					array('controller' => 'users', 'action' => 'profile', $post['User']['username'])); ?></td>
      <td class="leftalign">
      <?php if($post['User']['id'] == $user->id()): ?>
	  	<div class="forum-topic-cp">
        	<?php echo $html->link('Edit', 
						array('controller' => 'forum', 'action' => 'editpost', $post['ForumPost']['id'], $topicId, $forumId)); ?> |
            <?php echo $html->link('Delete', 
						array('controller' => 'forum', 'action' => 'deletepost', $post['ForumPost']['id'], $topicId, $forumId)); ?>
         <?php endif; ?>
        </div>
	  	<?php echo $post['ForumPost']['text']; ?>
        <div class="forum-topic-date">
        	<span>ID: <?php echo $post['ForumPost']['id']; ?></span>
        	Date: <?php echo $time->format('d.m.Y H:i',$post['ForumPost']['created']); ?>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
<div class="paging">
  <?php 
		$paginator->options(array('url' => $this->passedArgs,));
		echo $paginator->prev("« back", array(), null, array('class'=>'disabled'));
		echo $paginator->numbers(array('before' => '', 'after' => '', 'separator' => ''));
		echo $paginator->next('forward »', array(), null, array('class'=>'disabled'));
	?>
</div>
<div class="button-newtopic">
<?php echo $html->link('New topic', array('controller' => 'forum', 'action' => 'newtopic', $forumId)); ?>
<?php echo $html->link('Post reply', array('controller' => 'forum', 'action' => 'reply', $topicId, $forumId)); ?>
</div>