<?php
/**
 * Created by PhpStorm.
 * user: Shawn
 * Date: 5/09/2015
 * Time: 10:29 PM
 */ ?>

<!-- Right Nav Section -->
<ul class="nav navbar-nav navbar-right">
    <li>
        <a href="<?php echo Links::action_link('users/edit') ?>"> Hello
            <?php echo $_SESSION['user']['user_FirstName'] . " " . $_SESSION['user']['user_LastName']?>
        </a>
    </li>
    <li>
        <a href="<?php echo Links::action_link('users/logout') ?>">
            <span class="glyphicon glyphicon-log-out"></span> Logout</a>
    </li>
</ul>