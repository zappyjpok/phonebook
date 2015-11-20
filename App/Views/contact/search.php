<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 19/11/2015
 * Time: 10:42 AM
 */ ?>

<?php require_once('../App/Views/_layouts/_master.php') ?>
    <?php if(!empty($data['contacts'])) { ?>
        <div class="row">
            <?php require_once('../App/Views/contact/_table.php') ?>
        </div>
        <div class="row">
            <?php require_once('../App/Views/contact/_pagination.php') ?>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h3> Sorry, no one in your contacts matches your search </h3>
            </div>
        </div>

    <?php } ?>

<?php require_once('../App/Views/_layouts/_footer.php') ?>