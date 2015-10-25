<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 3/09/2015
 * Time: 6:00 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_header.php') ?>

    <div class="row">
        <div class="large-12">
            <?php
                if(isset($data['message'])) {  ?>
                    <div class="error">
                        <?php echo $data['message'] ?>
                    </div>
                    <?php } ?>



            <fieldset>
                <legend>Login</legend>
                <form action="check" method="post">
                    <div class="row">
                        <div class="medium-6 columns">
                            <label> Email: </label>
                            <input type="text" name="Email" value="<?php
                                if(isset($data['email'])) { echo $data['email']; }
                            ?>">
                        </div>
                        <div class="medium-6 columns">
                            <label> Password: </label>
                            <input type="password" name="Password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="medium-6 columns">
                            <input type="hidden" name="token" value="<?php echo $data['token']; ?> ">
                        </div>
                        <div class="medium-6 columns">
                            <input type="submit" class="button" value="Login">
                        </div>
                    </div>

                </form>
            </fieldset>
        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>