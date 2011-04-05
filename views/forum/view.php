<div class="forum-path"> 
<?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » 
<?php echo $html->link($forumName, array('controller' => 'forum', 'action' => 'view', $forumId)); ?> 
</div>
<div class="forum-index">
  <table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th width="295">Topic</th>
      <th width="130">Author</th>
      <th width="73">Replies</th>
      <th width="73">Views</th>
      <th width="188">Last post</th>
    </tr>
    <?php 
  	$i = 0;
  	if(!empty($topics)) { foreach($topics as $topic): 
  	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
  ?>
    <tr<?php echo $class; ?>>
      <td class="leftalign"><?php echo $html->link($topic['ForumTopic']['subject'], 
					array('controller' => 'forum', 'action' => 'topic', $topic['ForumTopic']['id'], $forumId)); ?></td>
      <td><?php echo $html->link($topic['User']['username'], 
					array('controller' => 'users', 'action' => 'profile', $topic['User']['username'])); ?></td>
      <td><?php echo $topic['ForumTopic']['replies']; ?></td>
      <td><?php echo $topic['ForumTopic']['views']; ?></td>
      <td>
	  	<?php 
			echo $html->link('»»»', 
				array(
					'controller' => 'forum', 
					'action' => 'topic', 
					$topic['ForumTopic']['id'], 
					$forumId,
					'page:' . $topic['ForumTopic']['last_post_page'].
					'#' . $topic['ForumTopic']['last_post_id']
				)
			); 
		?>
                    <br />
        <?php echo $time->format('d.m.Y H:i', $topic['ForumTopic']['last_post_created']); ?> | 
		<?php echo $html->link($topic['ForumTopic']['last_post_username'], 
					array('controller' => 'users', 'action' => 'profile', $topic['ForumTopic']['last_post_username'])); ?></td>
    </tr>
    <?php endforeach; }?>
  </table>
  <div class="paging">
    <?php 
		$paginator->options(array(
				'url' => $this->passedArgs,
			)
		);
		echo $paginator->prev("« back", array(), null, array('class'=>'disabled'));
		echo $paginator->numbers(array('before' => '', 'after' => '', 'separator' => ''));
		echo $paginator->next('forward »', array(), null, array('class'=>'disabled'));
	?>
  </div>
  <div class="button-newtopic"> <?php echo $html->link('New topic', array('controller' => 'forum', 'action' => 'newtopic', $forumId)); ?> </div>
</div>
