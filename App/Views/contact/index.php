<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 7/11/2015
 * Time: 4:28 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_master.php') ?>
    <div class="row">
        <a  href="<?php echo Links::action_link('contacts/create/' . $_SESSION['user']['user_id']) ?>" class="btn btn-primary">Add a Contact </a>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>