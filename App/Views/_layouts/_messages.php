<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 31/10/2015
 * Time: 3:49 PM
 */ ?>

<div class="row">
    <?php
    if(!empty($data['errors'])) {
        ?>
        <div class="alert-danger">
            <?php foreach($data['errors'] as $error){
                echo "<p class='spacing'>  $error </p>";
            } ?>
        </div>
    <?php } ?>

<?php
if(isset($_COOKIE['success'])) { ?>

    <div class="alert-success">
        <p class='spacing'>
            <?php echo $_COOKIE['success'];?>
        </p>
    </div>
<?php  } ?>
</div>