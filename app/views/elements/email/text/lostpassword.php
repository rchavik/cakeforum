Hello <?php echo $data['username']; ?>,
You made a request for lost password on CakeForum.
To reset your password please click on following link: http://<?php echo $host; ?>/users/reset/<?php echo $data['confirm']; ?>