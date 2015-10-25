<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 30/08/2015
 * Time: 3:13 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_header.php') ?>

    <pre>
       <?php print_r(User::authenticate('wonderful@gmail.com', 'secret')); ?>
    </pre>

    <h1> Next </h1>

    <pre>
        <?php print_r(User::find(18)) ?>
    </pre>

    <pre>
       <?php print_r($_SESSION['user']) ?>
    </pre>



<?php require_once('../App/Views/_layouts/_footer.php') ?>