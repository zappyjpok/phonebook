<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 5/09/2015
 * Time: 10:22 PM
 */ ?>

<?php  require_once('../App/Library/Paths/Links.php'); ?>

<nav class="top-bar" data-topbar>
    <ul class="title-area">
        <li class="name">
            <h3><a href="<?php echo Links::action_link('home') ?>">Home </a></h3>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
    </ul>

    <div class="top-bar-section">

        <?php

        if($loggedIn === true)
        {
            require_once('../App/Views/_layouts/_navLoggedIn.php');
        }
        else{
            require_once('../App/Views/_layouts/_navNotLoggedIn.php');
        }
        ?>


        <!-- Left Nav Section -->
        <ul class="left">
            <?php
                foreach($navValues as $topNav){ ?>
                    <li class="has-dropdown">
                        <a href="#">
                            <?php echo $topNav['name']; ?>
                        </a>
                        <ul class="dropdown">
                        <?php
                        foreach($topNav as $array){
                            if(is_array($array)) {
                                foreach ($array as $sub) {
                                    foreach($sub as $name)
                                    {
                                        if(!is_null($name)) { ?>
                                            <li>
                                                <a href="#">
                                                    <?php echo $name; ?>
                                                </a>
                                            </li> <?php
                                        }
                                    }
                                }
                            }
                        } ?>
                        </ul>
                    </li> <?php
                }
            ?>
        </ul>
    </div>

</nav>



