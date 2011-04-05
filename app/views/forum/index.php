<div class="subnav">
<?php echo $html->link('Unanswered topics', array('controller' => 'forum', 'action' => 'unanswered')); ?>
</div>
<div class="forum-index">
  <table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th width="259">Forum</th>
      <th width="112">Topics</th>
      <th width="129">Posts</th>
      <th width="259">Last topic</th>
    </tr>
    <?php 
		$i = 0;
		foreach($categories as $cat): 
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		?>
    <tr<?php echo $class; ?>>
      <td class="leftalign">
      	<div class="forum-index-title">
      	<?php echo $html->link($cat['ForumCategory']['name'],  
					array('controller' => 'forum', 'action' => 'view', 
					$cat['ForumCategory']['id'])); ?>
        </div>
      	<div class="forum-index-desc"><?php echo $cat['ForumCategory']['description']; ?></div>
      </td>
      <td><?php echo $cat['ForumCategory']['topics']; ?></td>
      <td><?php echo $cat['ForumCategory']['posts']; ?></td>
      <td>
      	<?php echo $html->link($cat['ForumCategory']['last_topic_subject'],  
					array('controller' => 'forum', 'action' => 'topic', $cat['ForumCategory']['last_topic_id'], $cat['ForumCategory']['id'])); ?>
                    <br />
	  	<?php echo $time->format('d.m.Y H:i', $cat['ForumCategory']['last_topic_created']); ?> | 
        <?php echo $html->link($cat['ForumCategory']['last_topic_username'], 
					array('controller' => 'users', 'action' => 'profile', $cat['ForumCategory']['last_topic_username'])); ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </table>
  </div>
