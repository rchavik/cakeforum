<div class="forum-path"> 
<?php echo $html->link('Forum index', array('controller' => 'forum', 'action' => 'index')); ?> » 
<?php echo $data['User']['username']; ?> 
</div>
<div class="forum-topic-title">User: <?php echo $data['User']['username']; ?></div>
Joined: <?php echo $time->format('d.m.Y H:i', $data['User']['created']); ?>