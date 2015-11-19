<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 31/10/2015
 * Time: 3:49 PM
 */ ?>

<div class="row">
    <div class="alert-danger">
        <?php
        if(!empty($data['errors']))
        {
            foreach ($data['errors'] as $error)
            {
                echo "<p class='spacing'>  $error </p>";
            }
        }


        if(!empty($_SESSION['errors']))
        {
            $errors = $this->sessions->withdrawl('errors');

            foreach($errors as $error)
            {
                echo "<p class='spacing'>  $error </p>";
            }
        }


        if(!empty($_COOKIE['error']))
        {
            echo $_COOKIE['error'];
        }
        ?>
    </div>


<?php
if(isset($_COOKIE['success'])) { ?>

    <div class="alert-success">
        <p class='spacing'>
            <?php echo $_COOKIE['success'];?>
        </p>
    </div>
<?php  } ?>
</div>