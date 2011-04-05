<p>Hello <?php echo $data['username']; ?>,<br />
You made a request for lost password on CakeForum.<br />
To reset your password please click on following link:<br />
<a href="http://<?php echo $host; ?>/users/reset/<?php echo $data['confirm']; ?>">http://<?php echo $host; ?>/users/reset/<?php echo $data['confirm']; ?></a><p>