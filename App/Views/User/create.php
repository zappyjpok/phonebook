<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 30/08/2015
 * Time: 5:18 PM
 */
?>
<?php require_once('../App/Views/_layouts/_header.php') ?>
    <div class="row">
        <div class="large-12">
            <fieldset>
                <legend>Register Here</legend>
                <form action="store" method="post">
                    <div class="row">
                        <div class="medium-6 columns">
                            <label> First Name: </label>
                            <input type="text" name="FirstName">
                        </div>
                        <div class="medium-6 columns">
                            <label> Last Name: </label>
                            <input type="text" name="LastName">
                        </div>
                    </div>
                    <div class="row">
                        <div class="medium-6 columns">
                            <label> Email: </label>
                            <input type="text" name="Email">
                        </div>
                        <div class="medium-6 columns">

                        </div>
                    </div>
                    <div class="row">
                        <div class="medium-6 columns">
                            <label> Password: </label>
                            <input type="password" name="Password">
                        </div>
                        <div class="medium-6 columns">
                            <label> Confirm Password: </label>
                            <input type="password" name="Confirm_Password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="medium-6 columns">
                            <input type="submit" class="button">
                        </div>
                    </div>

                </form>
            </fieldset>
        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>
