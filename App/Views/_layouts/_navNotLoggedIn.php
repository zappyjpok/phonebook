<?php
/**
 * Created by PhpStorm.
 * user: Shawn
 * Date: 5/09/2015
 * Time: 10:28 PM
 */ ?>

<!-- Right Nav Section -->
<ul class="nav navbar-nav navbar-right">
    <li>
        <a href="<?php echo Links::action_link('users/register') ?>">
        <span class="glyphicon glyphicon-user"></span> Sign Up</a>
    </li>
    <li>
        <a href="<?php echo Links::action_link('users/login') ?>">
        <span class="glyphicon glyphicon-log-in"></span> Login</a>
    </li>
</ul>