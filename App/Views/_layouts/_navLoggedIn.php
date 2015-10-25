<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 5/09/2015
 * Time: 10:29 PM
 */ ?>

<!-- Right Nav Section -->
<ul class="right">
    <li><a href="#"> Hello
            <?php echo $_SESSION['user'][0]['user_FirstName'] . " " . $_SESSION['user'][0]['user_LastName']?>
        </a>
    </li>
    <li>
        <a href="<?php echo Links::action_link('users/logout') ?>">Logout</a>
    </li>
</ul>