<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 19/11/2015
 * Time: 9:35 PM
 */ ?>

<div class="col-md-5 col-md-offset-4">
    <ul class="pagination">
        <?php  for($i = 1; $i < $data['pages'] + 1; $i++) {
            if($data['page'] == $i) { ?>
                <li class="active">
                    <a href="<?php echo Links::action_link($data['link'] . $i); ?>"> <?php echo Output::phpOutput($i); ?> </a>
                </li>
            <?php } else { ?>
                <li>
                    <a href="<?php echo Links::action_link($data['link'] . $i); ?>"> <?php echo Output::phpOutput($i); ?> </a>
                </li>
            <?php }
        } ?>
    </ul>
</div>